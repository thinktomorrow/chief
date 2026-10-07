<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\ExcelServiceProvider;
use Thinktomorrow\Chief\Admin\Users\Invites\Application\InviteUser;
use Thinktomorrow\Chief\Plugins\Export\ExportServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class ProcessEventsWithoutPluginTest extends ChiefTestCase
{
    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), ExcelServiceProvider::class, ExportServiceProvider::class];
    }

    public function test_chief_actions_work_without_audit_plugin(): void
    {
        Notification::fake();
        $admin = $this->fakeUser(['email' => 'admin@example.com', 'password' => bcrypt('password')]);

        $this->post(route('chief.back.login.store'), ['email' => $admin->email, 'password' => 'incorrect']);
        $this->post(route('chief.back.login.store'), ['email' => $admin->email, 'password' => 'password'])
            ->assertRedirect(route('chief.back.dashboard'));

        app(InviteUser::class)->handle($this->fakeUser(), $admin);
        $this->artisan('chief:export-text')->assertExitCode(0);

        $this->assertFalse(Schema::hasTable('chief_audit_events'));
    }
}
