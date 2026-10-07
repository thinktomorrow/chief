<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Tests;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;
use Thinktomorrow\Chief\Admin\Authorization\AuthorizationDefaults;
use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Plugins\Audit\AuditServiceProvider;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

final class AuditPermissionsCommandTest extends ChiefTestCase
{
    protected function getPackageProviders($app)
    {
        return [...parent::getPackageProviders($app), AuditServiceProvider::class];
    }

    public function test_audit_permissions_are_created_only_by_explicit_command_and_roles_are_chosen_separately(): void
    {
        $this->assertFalse(AuthorizationDefaults::permissions()->contains('view-related-audit'));
        $this->assertFalse(AuthorizationDefaults::permissions()->contains('view-full-audit'));
        $this->assertNotContains('view-related-audit', AuthorizationDefaults::roles()->get('admin'));

        $this->assertDatabaseMissing('permissions', ['name' => 'view-related-audit']);
        $this->assertDatabaseMissing('permissions', ['name' => 'view-full-audit']);

        $choices = ['admin', 'author', 'developer', '(geen rollen)'];

        $this->artisan('chief-audit:permissions')
            ->expectsChoice('Welke rollen krijgen view-full-audit? (meerdere keuzes gescheiden door komma’s)', ['author'], $choices)
            ->expectsChoice('Welke rollen krijgen view-related-audit? (meerdere keuzes gescheiden door komma’s)', ['admin', 'developer'], $choices)
            ->assertExitCode(0);

        $this->assertEquals('chief', Permission::findByName('view-related-audit', 'chief')->guard_name);
        $this->assertEquals('chief', Permission::findByName('view-full-audit', 'chief')->guard_name);
        $this->assertDatabaseMissing('permissions', ['name' => 'view-audit']);
        $this->assertTrue(Role::findByName('admin', 'chief')->hasPermissionTo('view-related-audit'));
        $this->assertTrue(Role::findByName('developer', 'chief')->hasPermissionTo('view-related-audit'));
        $this->assertFalse(Role::findByName('author', 'chief')->hasPermissionTo('view-related-audit'));
        $this->assertTrue(Role::findByName('author', 'chief')->hasPermissionTo('view-full-audit'));
        $this->assertFalse(Role::findByName('admin', 'chief')->hasPermissionTo('view-full-audit'));

        $this->artisan('chief-audit:permissions')
            ->expectsChoice('Welke rollen krijgen view-full-audit? (meerdere keuzes gescheiden door komma’s)', ['(geen rollen)'], $choices)
            ->expectsChoice('Welke rollen krijgen view-related-audit? (meerdere keuzes gescheiden door komma’s)', ['(geen rollen)'], $choices)
            ->assertExitCode(0);

        $this->assertEquals(1, Permission::where('name', 'view-related-audit')->count());
        $this->assertEquals(1, Permission::where('name', 'view-full-audit')->count());
        $this->assertTrue(Role::findByName('admin', 'chief')->hasPermissionTo('view-related-audit'));
        $this->assertTrue(Role::findByName('author', 'chief')->hasPermissionTo('view-full-audit'));
    }

    public function test_general_permission_check_includes_plugin_permissions_without_creating_them(): void
    {
        $this->artisan('chief:permissions:check')
            ->expectsOutput('Expected permissions: 21')
            ->expectsOutput('- view-full-audit')
            ->expectsOutput('- view-related-audit')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('permissions', ['name' => 'view-related-audit']);
    }

    public function test_default_selections_grant_basic_access_to_admin_and_developer_only(): void
    {
        $this->assertSame(0, Artisan::call('chief-audit:permissions', ['--no-interaction' => true]));

        $this->assertTrue(Role::findByName('admin', 'chief')->hasPermissionTo('view-related-audit'));
        $this->assertTrue(Role::findByName('developer', 'chief')->hasPermissionTo('view-related-audit'));
        $this->assertFalse(Role::findByName('author', 'chief')->hasPermissionTo('view-related-audit'));

        foreach (Role::all() as $role) {
            $this->assertFalse($role->hasPermissionTo('view-full-audit'));
        }
    }

    public function test_accepting_interactive_defaults_assigns_basic_audit_to_admin_and_developer(): void
    {
        $tester = new CommandTester(Artisan::all()['chief-audit:permissions']);
        $tester->setInputs(['', '']);

        $this->assertSame(0, $tester->execute([]));
        $this->assertTrue(Role::findByName('admin', 'chief')->hasPermissionTo('view-related-audit'));
        $this->assertTrue(Role::findByName('developer', 'chief')->hasPermissionTo('view-related-audit'));
        $this->assertFalse(Role::findByName('author', 'chief')->hasPermissionTo('view-full-audit'));
    }
}
