<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Admin\Authorization;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Resource\PermissionScopedResource;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

final class ResourcePermissionTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutDefaultAuthorization();
    }

    public function test_it_builds_resource_scoped_permissions(): void
    {
        $this->assertInstanceOf(PermissionScopedResource::class, new ArticlePageResource);
        $this->assertEquals('view-page', chiefPermission(ArticlePageResource::class, 'view'));
        $this->assertEquals('view-opleiding', chiefPermission('opleiding', 'view'));
        $this->assertEquals([
            'view-page',
            'create-page',
            'update-page',
            'delete-page',
        ], ChiefResourcePermissions::permissionsFor(new ArticlePageResource));
    }

    public function test_it_can_use_custom_resource_permission_scope_and_abilities(): void
    {
        $resource = new class extends ArticlePageResource
        {
            public static function permissionScope(): string
            {
                return 'custom_scope';
            }

            public static function permissionAbilities(): array
            {
                return ['view', 'publish'];
            }
        };

        $this->assertEquals([
            'view-custom_scope',
            'publish-custom_scope',
        ], ChiefResourcePermissions::permissionsFor($resource));
    }

    public function test_missing_resource_permission_is_reported_and_denied(): void
    {
        $scope = 'missing_scope_'.Str::random(8);
        $resource = new class extends ArticlePageResource
        {
            public static string $scope;

            public static function permissionScope(): string
            {
                return self::$scope;
            }
        };
        $resource::$scope = $scope;

        Log::spy();

        $admin = $this->fakeUser();

        $this->assertFalse(ChiefResourcePermissions::adminCanResource($admin, $resource, 'view'));

        Log::shouldHaveReceived('warning')->once()->with('Missing Chief permission checked.', [
            'permission' => 'view-'.$scope,
        ]);
    }

    public function test_missing_resource_permission_is_not_reported_when_disabled(): void
    {
        $scope = 'missing_scope_'.Str::random(8);
        $resource = new class extends ArticlePageResource
        {
            public static string $scope;

            public static function permissionScope(): string
            {
                return self::$scope;
            }
        };
        $resource::$scope = $scope;

        config()->set('chief.permissions.report_missing', false);
        config()->set('chief.permissions.log_missing', false);
        Log::spy();

        $admin = $this->fakeUser();

        $this->assertFalse(ChiefResourcePermissions::adminCanResource($admin, $resource, 'view'));

        Log::shouldNotHaveReceived('warning');
    }

    public function test_missing_resource_permission_can_throw_in_strict_mode(): void
    {
        $scope = 'missing_scope_'.Str::random(8);
        $resource = new class extends ArticlePageResource
        {
            public static string $scope;

            public static function permissionScope(): string
            {
                return self::$scope;
            }
        };
        $resource::$scope = $scope;

        config()->set('chief.permissions.strict_missing', true);

        $this->expectException(PermissionDoesNotExist::class);

        ChiefResourcePermissions::adminCanResource($this->fakeUser(), $resource, 'view');
    }

    public function test_default_page_permission_scope_is_used_for_page_resources(): void
    {
        Permission::create(['name' => 'view-page']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('view-page');

        $admin = $this->fakeUser();
        $admin->assignRole($role);

        $this->assertTrue(ChiefResourcePermissions::adminCanResource($admin, ArticlePageResource::class, 'view'));
    }

    public function test_custom_resource_permission_scope_is_used_when_defined(): void
    {
        Permission::create(['name' => 'view-page']);
        Permission::create(['name' => 'view-custom_scope']);

        $resource = new class extends ArticlePageResource
        {
            public static function permissionScope(): string
            {
                return 'custom_scope';
            }
        };

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo('view-page');

        $admin = $this->fakeUser();
        $admin->assignRole($role);

        $this->assertFalse(ChiefResourcePermissions::adminCanResource($admin, $resource, 'view'));
    }

    public function test_audit_command_can_sync_registered_resource_permissions(): void
    {
        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class, PageManager::class);

        $this->artisan('chief:permissions:audit', ['--sync' => true])
            ->assertExitCode(0);

        $permissionNames = Permission::pluck('name')->all();

        $this->assertContains('view-page', $permissionNames);
        $this->assertContains('create-page', $permissionNames);
        $this->assertContains('update-page', $permissionNames);
        $this->assertContains('delete-page', $permissionNames);
        $this->assertContains('view-role', $permissionNames);
    }

    public function test_audit_command_without_sync_reports_missing_permissions_without_creating_them(): void
    {
        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class, PageManager::class);

        $this->artisan('chief:permissions:audit')
            ->expectsOutput('Expected permissions: 20')
            ->expectsOutput('Missing permissions: 20')
            ->expectsOutput('- create-page')
            ->assertExitCode(0);

        $this->assertCount(0, Permission::all());
    }

    public function test_audit_command_sync_is_idempotent(): void
    {
        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class, PageManager::class);

        $this->artisan('chief:permissions:audit', ['--sync' => true])
            ->expectsOutput('20 missing permission(s) created.')
            ->assertExitCode(0);

        $this->assertCount(20, Permission::all());

        $this->artisan('chief:permissions:audit', ['--sync' => true])
            ->expectsOutput('0 missing permission(s) created.')
            ->assertExitCode(0);

        $this->assertCount(20, Permission::all());
    }

    public function test_audit_command_reports_unused_permissions(): void
    {
        Permission::create(['name' => 'old-custom-permission']);

        $this->artisan('chief:permissions:audit')
            ->expectsOutput('Expected permissions: 20')
            ->expectsOutput('Unused permissions: 1')
            ->expectsOutput('- old-custom-permission')
            ->assertExitCode(0);
    }
}
