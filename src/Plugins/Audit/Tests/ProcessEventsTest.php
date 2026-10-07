<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\ExcelServiceProvider;
use RuntimeException;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Admin\Users\Application\DeleteUser;
use Thinktomorrow\Chief\Admin\Users\Invites\Application\InviteUser;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Plugins\Export\ExportServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class ProcessEventsTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), ExcelServiceProvider::class, ExportServiceProvider::class, AuditServiceProvider::class];
    }

    public function test_http_login_results_record_one_event_with_proven_and_unproven_actors(): void
    {
        $admin = $this->fakeUser(['email' => 'admin@example.com', 'password' => bcrypt('password'), 'enabled' => true]);

        $this->post(route('chief.back.login.store'), ['email' => $admin->email, 'password' => 'incorrect']);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.login.failure', 'actor_type' => 'external', 'outcome' => 'failure', 'model_id' => null]);
        $failed = DB::table('chief_audit_events')->first();
        $this->assertSame(['name' => 'Unverified login attempt'], json_decode($failed->actor_snapshot, true));
        $this->assertSame(['submitted_email' => 'admin@example.com'], json_decode($failed->context, true));

        $this->post(route('chief.back.login.store'), ['email' => $admin->email, 'password' => 'password']);
        $this->assertDatabaseCount('chief_audit_events', 2);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.login.success', 'actor_type' => 'admin', 'outcome' => 'success', 'model_id' => null]);
        $success = DB::table('chief_audit_events')->where('type', 'chief.login.success')->first();
        $this->assertSame(['name' => $admin->fullname, 'id' => (string) $admin->id], json_decode($success->actor_snapshot, true));
        $this->assertNotNull($success->occurred_at);

        ChiefResourcePermissions::syncMissingPermissions(AuditServiceProvider::PERMISSIONS);
        $admin->givePermissionTo('view-related-audit');
        $this->actingAs($admin, 'chief')->get(route('chief.audit.index'))->assertOk()->assertDontSee('Admin login failed')->assertDontSee('Admin logged in');
        $admin->givePermissionTo('view-full-audit');
        $this->get(route('chief.audit.index'))->assertOk()->assertSee('Admin login failed')->assertSee('Admin logged in');
    }

    public function test_invitation_and_deletion_are_recorded_once_and_rollback_with_the_action(): void
    {
        Notification::fake();
        $inviter = $this->fakeUser();
        $invitee = $this->fakeUser();

        $this->actingAs($inviter, 'chief');
        app(InviteUser::class)->handle($invitee, $inviter);

        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.user.invited', 'actor_type' => 'admin', 'outcome' => 'success']);
        $invitation = DB::table('chief_audit_events')->first();
        $this->assertSame(['name' => $inviter->fullname, 'id' => (string) $inviter->id], json_decode($invitation->actor_snapshot, true));
        $this->assertSame(['invitee_email' => $invitee->email], json_decode($invitation->context, true));

        try {
            DB::transaction(function () use ($invitee): void {
                app(DeleteUser::class)->handle($invitee);
                throw new RuntimeException('rollback');
            });
        } catch (RuntimeException $exception) {
            $this->assertSame('rollback', $exception->getMessage());
        }

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertNotNull(User::find($invitee->id));
        app(DeleteUser::class)->handle(User::findOrFail($invitee->id));
        $this->assertDatabaseCount('chief_audit_events', 2);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.user.deleted', 'category' => 'users']);
    }

    public function test_real_export_command_records_a_system_event_once_after_success(): void
    {
        $this->actingAs($this->admin(), 'chief');
        $this->artisan('chief:export-text')->assertExitCode(0);

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.export.text', 'category' => 'export', 'actor_type' => 'system', 'outcome' => 'success', 'model_id' => null]);
    }

    public function test_http_invitation_records_exactly_one_historical_event(): void
    {
        Notification::fake();
        $inviter = $this->admin();
        Role::firstOrCreate(['name' => 'author', 'guard_name' => 'chief']);

        $this->actingAs($inviter, 'chief')->post(route('chief.back.users.store'), [
            'firstname' => 'New', 'lastname' => 'User', 'email' => 'invite@example.com', 'roles' => ['author'],
        ])->assertRedirect(route('chief.back.users.index'));

        $this->assertDatabaseCount('chief_audit_events', 1);
        $this->assertDatabaseHas('chief_audit_events', ['type' => 'chief.user.invited', 'actor_type' => 'admin']);
        $this->assertSame(['name' => $inviter->fullname, 'id' => (string) $inviter->id], json_decode(DB::table('chief_audit_events')->first()->actor_snapshot, true));
    }
}
