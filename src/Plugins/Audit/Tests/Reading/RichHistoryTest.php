<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests\Reading;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Audit\History;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditRichData;
use Thinktomorrow\Chief\Plugins\Audit\Recording\AuditModelDTO;
use Thinktomorrow\Chief\Plugins\Audit\Recording\RichData;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class RichHistoryTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_rich_data_is_lazy_and_partial_projection_is_scoped_to_visible_models(): void
    {
        $article = $this->setupAndCreateArticle();
        $event = History::log(type: 'project.bulk', actorType: 'system', actorSnapshot: ['name' => 'System'], models: [
            new AuditModelDTO('hidden.type', '12', ['name' => 'Hidden model']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible model']),
        ], richData: [
            RichData::text('Hidden text', model: 0),
            RichData::metadata(['secret' => 'Hidden metadata'], model: 0),
            RichData::text('Visible text', model: 1),
            RichData::mailPreview('<h1>Historical mail</h1>', ['subject' => 'Visible subject'], model: 1),
            RichData::text('Shared secret'),
        ]);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-related-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $url = route('chief.audit.rich-data', $event->getKey());

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertDontSee('Visible text')->assertDontSee('Hidden text')->assertDontSee('Historical mail');
        $this->get($url)->assertOk()->assertSee('Visible text')->assertSee('Historische HTML')->assertSee('Visible subject')
            ->assertDontSee('Hidden text')->assertDontSee('Hidden metadata')->assertDontSee('Shared secret');
        $this->get(route('chief.audit.rich-html', [$event->getKey(), 4]))->assertOk()->assertSee('Historical mail');
        $this->get(route('chief.audit.rich-data', $event->getKey()))->assertDontSee('Shared secret');

        $viewer->revokePermissionTo(ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $this->get($url)->assertNotFound();

        $full = $this->fakeUser();
        $full->givePermissionTo('view-full-audit');
        $this->actingAs($full, 'chief')->get($url)->assertOk()->assertSee('Hidden text')->assertSee('Shared secret');
    }

    public function test_actor_only_projection_never_reveals_rich_data(): void
    {
        $article = $this->setupAndCreateArticle();
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-related-audit');
        $event = History::log(type: 'project.own', actorType: 'admin', actorSnapshot: ['name' => 'Actor', 'id' => (string) $viewer->id], models: [
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Secret']),
        ], richData: [RichData::text('Secret content', model: 0)]);

        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))->assertOk()->assertSee('project.own')->assertDontSee('Secret content');
        $this->get(route('chief.audit.rich-data', $event->getKey()))->assertNotFound();
    }

    public function test_hidden_only_rich_status_does_not_appear_in_a_partial_timeline_or_details(): void
    {
        $article = $this->setupAndCreateArticle();
        config()->set('chief.audit.rich_limits.text', 2);
        $event = History::log(type: 'project.partial', actorType: 'system', actorSnapshot: ['name' => 'System'], models: [
            new AuditModelDTO('hidden.type', '12', ['name' => 'Hidden']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible']),
        ], richData: [RichData::text('hidden evidence', model: 0)]);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-related-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.index'))
            ->assertOk()->assertSee('project.partial')->assertDontSee('Historische inhoud')->assertDontSee('Niet vastgelegd');
        $this->get(route('chief.audit.rich-data', $event->getKey()))->assertNotFound();
        $this->get(route('chief.audit.rich-reference', [$event->getKey(), 1]))->assertNotFound();

        $full = $this->fakeUser();
        $full->givePermissionTo('view-full-audit');
        $this->actingAs($full, 'chief')->get(route('chief.audit.rich-data', $event->getKey()))->assertOk()->assertSee('Niet beschikbaar door limiet of vastlegfout');
    }

    public function test_html_and_file_references_are_isolated_and_reauthorized(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('audit/example.txt', 'file evidence');
        $event = History::log(type: 'project.mail', actorType: 'system', actorSnapshot: ['name' => 'System'], richData: [
            RichData::html('<script>alert(1)</script><img src="https://example.com/tracker">'),
            RichData::reference('example.txt', 'local', 'audit/example.txt'),
        ]);
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-full-audit');
        $this->actingAs($viewer, 'chief');
        $details = $this->get(route('chief.audit.rich-data', $event->getKey()))->assertOk()
            ->assertSee('sandbox=""', false)->assertDontSee('<script>alert(1)</script>', false);
        $this->get(route('chief.audit.rich-html', [$event->getKey(), 1]))->assertOk()
            ->assertHeader('Content-Security-Policy', "sandbox; default-src 'none'; style-src 'unsafe-inline'; img-src data:");
        $this->get(route('chief.audit.rich-reference', [$event->getKey(), 2]))->assertOk()->assertHeader('content-disposition');
        Storage::disk('local')->delete('audit/example.txt');
        $this->get(route('chief.audit.rich-reference', [$event->getKey(), 2]))->assertNotFound();
        $this->get(route('chief.audit.rich-data', $event->getKey()))->assertSee('Referentie onbeschikbaar')->assertDontSee('example.txt');
        $viewer->revokePermissionTo('view-full-audit');
        $this->get(route('chief.audit.rich-html', [$event->getKey(), 1]))->assertForbidden();
        $this->get(route('chief.audit.rich-reference', [$event->getKey(), 2]))->assertForbidden();
    }

    public function test_a_partial_viewer_can_download_only_references_on_visible_links(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('audit/hidden.txt', 'hidden bytes');
        Storage::disk('local')->put('audit/visible.txt', 'visible bytes');
        $article = $this->setupAndCreateArticle();
        $event = History::log(type: 'project.files', actorType: 'system', actorSnapshot: ['name' => 'System'], models: [
            new AuditModelDTO('hidden.type', '12', ['name' => 'Hidden']),
            new AuditModelDTO($article->getMorphClass(), (string) $article->getKey(), ['name' => 'Visible']),
        ], richData: [
            RichData::reference('hidden.txt', 'local', 'audit/hidden.txt', model: 0),
            RichData::reference('visible.txt', 'local', 'audit/visible.txt', model: 1),
        ]);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-related-audit', ChiefResourcePermissions::permissionFor(ArticlePageResource::class, 'view'));
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.rich-data', $event->getKey()))
            ->assertOk()->assertSee('visible.txt')->assertDontSee('hidden.txt');
        $this->get(route('chief.audit.rich-reference', [$event->getKey(), 1]))->assertNotFound();
        $this->get(route('chief.audit.rich-reference', [$event->getKey(), 2]))->assertOk();

        $article->delete();
        $this->get(route('chief.audit.rich-reference', [$event->getKey(), 2]))->assertNotFound();
    }

    public function test_limits_and_capture_failure_keep_the_base_event_with_safe_status(): void
    {
        config()->set('chief.audit.rich_limits.text', 3);
        $event = History::log(type: 'project.limit', actorType: 'system', actorSnapshot: ['name' => 'System'], richData: [RichData::text('too long')]);
        $this->assertDatabaseHas('chief_audit_events', ['id' => $event->getKey()]);
        $this->assertDatabaseCount('chief_audit_rich_data', 1);
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-full-audit');
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.rich-data', $event->getKey()))
            ->assertOk()->assertSee('Niet beschikbaar door limiet of vastlegfout')->assertDontSee('too long');
    }

    public function test_failed_capture_does_not_leave_partial_content_and_caller_rollback_removes_both(): void
    {
        AuditRichData::creating(function (AuditRichData $piece): void {
            if ($piece->status === 'available') {
                throw new \RuntimeException('Sensitive capture failure');
            }
        });

        $event = History::log(type: 'project.error', actorType: 'system', actorSnapshot: ['name' => 'System'], richData: [RichData::text('Private data')]);
        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $viewer = $this->fakeUser();
        $viewer->givePermissionTo('view-full-audit');
        $this->actingAs($viewer, 'chief')->get(route('chief.audit.rich-data', $event->getKey()))
            ->assertOk()->assertSee('Niet beschikbaar door limiet of vastlegfout')->assertDontSee('Sensitive capture failure')->assertDontSee('Private data');

        DB::beginTransaction();
        History::log(type: 'project.rollback', actorType: 'system', actorSnapshot: ['name' => 'System'], richData: [RichData::metadata(['safe' => 'value'])]);
        DB::rollBack();
        $this->assertDatabaseMissing('chief_audit_events', ['type' => 'project.rollback']);
    }
}
