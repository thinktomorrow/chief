<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class SentMailHistoryTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_a_real_sent_invitation_preserves_its_rendered_preview_for_authorized_readers(): void
    {
        config()->set('mail.default', 'array');
        config()->set('queue.default', 'sync');
        $admin = $this->admin();
        Role::firstOrCreate(['name' => 'author', 'guard_name' => 'chief']);

        $this->actingAs($admin, 'chief')->post(route('chief.back.users.store'), [
            'firstname' => 'New', 'lastname' => 'User', 'email' => 'invite@example.com', 'roles' => ['author'],
        ])->assertRedirect(route('chief.back.users.index'));

        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.mail.invitation.sent', 'outcome' => 'success']);
        $event = DB::table('chief_audit_events')->where('type', 'chief.mail.invitation.sent')->first();
        $this->assertDatabaseCount('chief_audit_events', 2);
        $piece = DB::table('chief_audit_rich_data')->where('event_id', $event->id)->first();
        $this->assertSame('available', $piece->status);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $limited = $this->fakeUser();
        $limited->givePermissionTo('view-related-audit');
        $this->actingAs($limited, 'chief')->get(route('chief.audit.index'))->assertOk()->assertDontSee('chief.mail.invitation.sent');
        $this->get(route('chief.audit.rich-data', $event->id))->assertNotFound();

        $admin->givePermissionTo('view-full-audit');
        $this->actingAs($admin, 'chief')->get(route('chief.audit.index'))->assertOk()->assertSee('chief.mail.invitation.sent')->assertDontSee('invite@example.com');
        $this->get(route('chief.audit.rich-data', $event->id))->assertOk()->assertSee('Uitnodiging tot Chief')->assertDontSee('accept_url');
        $previewUrl = route('chief.audit.rich-html', [$event->id, $piece->id]);
        $original = $this->get($previewUrl)->assertOk()->assertSee('invite', false)
            ->assertHeader('Content-Security-Policy', "sandbox; default-src 'none'; style-src 'unsafe-inline'; img-src data:");
        config()->set('app.url', 'https://changed.example');
        $this->assertSame($original->getContent(), $this->get($previewUrl)->assertOk()->getContent());
    }

    public function test_vetoed_mail_does_not_record_a_sent_outcome(): void
    {
        config()->set('mail.default', 'array');
        config()->set('queue.default', 'sync');
        Event::listen(MessageSending::class, fn () => false);
        $admin = $this->admin();
        Role::firstOrCreate(['name' => 'author', 'guard_name' => 'chief']);

        $this->actingAs($admin, 'chief')->post(route('chief.back.users.store'), [
            'firstname' => 'New', 'lastname' => 'User', 'email' => 'invite@example.com', 'roles' => ['author'],
        ])->assertRedirect(route('chief.back.users.index'));

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.user.invited']);
        $this->assertDatabaseCount('chief_audit_rich_data', 0);
    }

    public function test_preview_limit_preserves_sent_event_without_truncated_evidence(): void
    {
        config()->set('mail.default', 'array');
        config()->set('queue.default', 'sync');
        config()->set('chief.audit.rich_limits.mailpreview', 1);
        $admin = $this->admin();
        Role::firstOrCreate(['name' => 'author', 'guard_name' => 'chief']);

        $this->actingAs($admin, 'chief')->post(route('chief.back.users.store'), [
            'firstname' => 'New', 'lastname' => 'User', 'email' => 'invite@example.com', 'roles' => ['author'],
        ])->assertRedirect(route('chief.back.users.index'));

        $event = DB::table('chief_audit_events')->where('type', 'chief.mail.invitation.sent')->first();
        $piece = DB::table('chief_audit_rich_data')->where('event_id', $event->id)->first();
        $this->assertSame('success', $event->outcome);
        $this->assertSame('limit', $piece->status);
        $this->assertNull($piece->content);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $admin->givePermissionTo('view-full-audit');
        $this->get(route('chief.audit.rich-data', $event->id))->assertOk()->assertSee('Niet vastgelegd')->assertDontSee('invite@example.com');
        $this->get(route('chief.audit.rich-html', [$event->id, $piece->id]))->assertNotFound();
    }

    public function test_mail_event_without_a_preview_reports_never_captured_only_to_full_viewers(): void
    {
        $event = History::log(type: 'chief.mail.invitation.sent', actorType: 'system', actorSnapshot: ['name' => 'Chief'], outcome: 'success');
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $limited = $this->fakeUser();
        $limited->givePermissionTo('view-related-audit');
        $this->actingAs($limited, 'chief')->get(route('chief.audit.event-details', $event->getKey()))->assertNotFound();

        $full = $this->fakeUser();
        $full->givePermissionTo('view-full-audit');
        $this->actingAs($full, 'chief')->get(route('chief.audit.event-details', $event->getKey()))->assertOk()->assertSee('Nooit vastgelegd');
    }

    public function test_expired_invitation_preview_retains_the_event_and_its_restricted_removed_status(): void
    {
        Carbon::setTestNow('2026-10-07 12:00:00');
        config()->set('mail.default', 'array');
        config()->set('queue.default', 'sync');
        $admin = $this->admin();
        Role::firstOrCreate(['name' => 'author', 'guard_name' => 'chief']);

        $this->actingAs($admin, 'chief')->post(route('chief.back.users.store'), [
            'firstname' => 'New', 'lastname' => 'User', 'email' => 'invite@example.com', 'roles' => ['author'],
        ])->assertRedirect(route('chief.back.users.index'));

        $event = DB::table('chief_audit_events')->where('type', 'chief.mail.invitation.sent')->first();
        $piece = DB::table('chief_audit_rich_data')->where('event_id', $event->id)->first();
        config()->set('chief-audit.events_days', null);
        config()->set('chief-audit.rich_data_days', 1);
        Carbon::setTestNow('2026-10-09 12:00:00');

        $this->assertSame(0, Artisan::call('chief-audit:cleanup'));
        $this->assertDatabaseHas('chief_audit_events', ['id' => $event->id, 'has_rich_data' => true]);
        $this->assertDatabaseHas('chief_audit_rich_data', ['id' => $piece->id, 'status' => 'removed', 'content' => null, 'metadata' => null]);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $limited = $this->fakeUser();
        $limited->givePermissionTo('view-related-audit');
        $this->actingAs($limited, 'chief')->get(route('chief.audit.index'))->assertOk()->assertDontSee('chief.mail.invitation.sent');
        $this->get(route('chief.audit.rich-data', $event->id))->assertNotFound();

        $admin->givePermissionTo('view-full-audit');
        $this->actingAs($admin, 'chief')->get(route('chief.audit.index'))->assertOk()->assertSee('chief.mail.invitation.sent');
        $this->get(route('chief.audit.rich-data', $event->id))->assertOk()->assertSee('Verwijderd door bewaring')->assertDontSee('invite@example.com');
        $this->get(route('chief.audit.rich-html', [$event->id, $piece->id]))->assertNotFound();
    }
}
