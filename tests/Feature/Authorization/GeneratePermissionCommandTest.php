<?php

namespace Chief\Tests\Feature\Authorization;

use Chief\Authorization\Permission;
use Chief\Authorization\Role;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;

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
    function permissions_can_be_generated()
    {
        $this->artisan('chief:permission', [
            'name' => 'new permission'
        ]);

        $this->assertCount(4, Permission::all());

        $permissionNames = Permission::all()->pluck('name')->toArray();
        $this->assertContains('view-new-permission', $permissionNames);
        $this->assertContains('create-new-permission', $permissionNames);
        $this->assertContains('update-new-permission', $permissionNames);
        $this->assertContains('delete-new-permission', $permissionNames);
    }

    /** @test */
    function permissions_can_be_assigned_to_a_role()
    {
        Role::create(['name' => 'admin']);

        $this->artisan('chief:permission', [
            'name' => 'new permission',
            '--role' => 'admin'
        ]);

        $role = Role::findByName('admin');
        $this->assertCount(4, $role->permissions);
    }

    /** @test */
    function permissions_can_be_assigned_to_multiple_roles()
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'author']);
        Role::create(['name' => 'guest']);

        $this->artisan('chief:permission', [
            'name' => 'new permission',
            '--role' => 'admin, author'
        ]);

        $this->assertCount(4, Role::findByName('admin')->permissions);
        $this->assertCount(4, Role::findByName('author')->permissions);

        // Assert no other roles receive these permissions
        $this->assertCount(0, Role::findByName('guest')->permissions);
    }
}