<?php

namespace Chief\Tests\Feature\Authorization;

use Chief\Authorization\Permission;
use Chief\Authorization\Role;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;

class GeneratePermissionCommandTest extends TestCase
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

        $this->artisan('chief:permission');
    }

    /** @test */
    function single_permission_can_be_generated()
    {
        Role::create(['name' => 'admin']);

        $this->artisan('chief:permission', [
            'name' => 'view-role',
            '--role' => 'admin'
        ]);

        $role = Role::findByName('admin');
        $this->assertCount(1, $role->permissions);

        $this->assertEquals('view-role', Permission::first()->name);
    }

    /** @test */
    function permissions_can_be_generated()
    {
        $this->artisan('chief:permission', [
            'name' => 'permission'
        ]);

        $this->assertCount(4, Permission::all());

        $permissionNames = Permission::all()->pluck('name')->toArray();
        $this->assertContains('view-permission', $permissionNames);
        $this->assertContains('create-permission', $permissionNames);
        $this->assertContains('update-permission', $permissionNames);
        $this->assertContains('delete-permission', $permissionNames);

        // Assert the proper guard is used
        $this->assertEquals('admin', Permission::first()->guard_name);
    }

    /** @test */
    function permissions_can_be_assigned_to_a_role()
    {
        Role::create(['name' => 'admin']);

        $this->artisan('chief:permission', [
            'name' => 'view-role',
            '--role' => 'admin'
        ]);

        $role = Role::findByName('admin');
        $this->assertCount(1, $role->permissions);

        $permissionNames = Permission::all()->pluck('name')->toArray();
        $this->assertContains('view-role', $permissionNames);
    }

    /** @test */
    function permissions_can_be_assigned_to_multiple_roles()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'author']);
        Role::create(['name' => 'guest']);

        $this->artisan('chief:permission', [
            'name' => 'permission',
            '--role' => 'admin, author'
        ]);

        $this->assertCount(4, Role::findByName('admin')->permissions);
        $this->assertCount(4, Role::findByName('author')->permissions);

        // Assert no other roles receive these permissions
        $this->assertCount(0, Role::findByName('guest')->permissions);
    }
}