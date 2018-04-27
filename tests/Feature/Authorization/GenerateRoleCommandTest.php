<?php

namespace Chief\Tests\Feature\Authorization;

use Chief\Authorization\Permission;
use Chief\Authorization\Role;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class GenerateRoleCommandTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    function it_requires_a_name_parameter()
    {
        $this->expectException(\RuntimeException::class);

        $this->artisan('chief:role');
    }

    /** @test */
    function a_role_can_be_generated()
    {
        $this->artisan('chief:role', [
            'name' => 'new role'
        ]);

        $this->assertCount(1, Role::all());

        // Assert the proper guard is used
        $this->assertEquals('admin', Role::first()->guard_name);
    }

    /** @test */
    function a_role_can_be_given_permissions()
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
    function a_role_can_be_given_permissions_by_scope()
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
    function when_assigning_permission_scope_all_permissions_must_be_existing()
    {
        $this->expectException(PermissionDoesNotExist::class);

        Permission::create(['name' => 'view-user']);

        $this->artisan('chief:role', [
            'name' => 'new role',
            '--permissions' => 'user'
        ]);
    }

    /** @test */
    function permissions_can_be_assigned_to_multiple_roles()
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
}