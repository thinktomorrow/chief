<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Authorization;

use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class GenerateRoleCommandTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutDefaultAuthorization();
    }

    /** @test */
    public function it_requires_a_name_parameter()
    {
        $this->expectException(\RuntimeException::class);

        $this->artisan('chief:role');
    }

    /** @test */
    public function a_role_can_be_generated()
    {
        $this->artisan('chief:role', [
            'name' => 'new role'
        ]);

        $this->assertCount(1, Role::all());

        // Assert the proper guard is used
        $this->assertEquals('chief', Role::first()->guard_name);
    }

    /** @test */
    public function a_role_can_be_given_permissions()
    {
        Permission::create(['name' => 'view-user']);

        $this->artisan('chief:role', [
            'name' => 'new role',
            '--permissions' => 'view-user'
        ]);

        $role = Role::findByName('new role');
        $this->assertCount(1, $role->permissions);
    }

    /** @test */
    public function a_role_can_be_given_permissions_by_scope()
    {
        Permission::create(['name' => 'view-user']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'update-user']);
        Permission::create(['name' => 'delete-user']);

        $this->artisan('chief:role', [
            'name' => 'new role',
            '--permissions' => 'user'
        ]);

        $role = Role::findByName('new role');
        $this->assertCount(4, $role->permissions);
    }

    /** @test */
    public function when_assigning_permission_scope_all_permissions_must_be_existing()
    {
        $this->expectException(PermissionDoesNotExist::class);

        Permission::create(['name' => 'view-user']);

        $this->artisan('chief:role', [
            'name' => 'new role',
            '--permissions' => 'user'
        ]);
    }

    /** @test */
    public function permissions_can_be_assigned_to_multiple_roles()
    {
        Permission::create(['name' => 'view-user']);
        Permission::create(['name' => 'create-user']);

        Permission::create(['name' => 'unknown']);

        $this->artisan('chief:role', [
            'name' => 'new role',
            '--permissions' => 'view-user, create-user'
        ]);

        $role = Role::findByName('new role');
        $this->assertCount(2, $role->permissions);
    }

    /** @test */
    public function permissions_can_be_assigned_as_scopes()
    {
        Permission::create(['name' => 'view-user']);
        Permission::create(['name' => 'create-user']);
        Permission::create(['name' => 'update-user']);
        Permission::create(['name' => 'delete-user']);
        Permission::create(['name' => 'view-page']);
        Permission::create(['name' => 'create-page']);
        Permission::create(['name' => 'update-page']);
        Permission::create(['name' => 'delete-page']);

        Permission::create(['name' => 'unknown']);

        $this->artisan('chief:role', [
            'name' => 'new role',
            '--permissions' => 'user, page'
        ]);

        $role = Role::findByName('new role');
        $this->assertCount(8, $role->permissions);

        $permissionNames = Permission::all()->pluck('name')->toArray();
        $this->assertContains('view-user', $permissionNames);
        $this->assertContains('create-user', $permissionNames);
        $this->assertContains('update-user', $permissionNames);
        $this->assertContains('delete-user', $permissionNames);
        $this->assertContains('view-page', $permissionNames);
        $this->assertContains('create-page', $permissionNames);
        $this->assertContains('update-page', $permissionNames);
        $this->assertContains('delete-page', $permissionNames);
    }
}
