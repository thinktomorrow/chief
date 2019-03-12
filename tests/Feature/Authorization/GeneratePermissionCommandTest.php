<?php

namespace Thinktomorrow\Chief\Tests\Feature\Authorization;

use Thinktomorrow\Chief\Authorization\Permission;
use Thinktomorrow\Chief\Authorization\Role;
use Thinktomorrow\Chief\Tests\TestCase;

class GeneratePermissionCommandTest extends TestCase
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

        $this->artisan('chief:permission');
    }

    /** @test */
    public function single_permission_can_be_generated()
    {
        Role::create(['name' => 'new admin']);

        $this->artisan('chief:permission', [
            'name'   => 'view-ability',
            '--role' => 'new admin'
        ]);

        $role = Role::findByName('new admin');
        $this->assertCount(1, $role->permissions);

        $this->assertEquals('view-ability', $role->permissions->first()->name);
    }

    /** @test */
    public function permissions_can_be_generated()
    {
        $this->artisan('chief:permission', [
            'name' => 'ability'
        ]);

        $this->assertCount(4, Permission::all());

        $permissionNames = Permission::all()->pluck('name')->toArray();
        $this->assertContains('view-ability', $permissionNames);
        $this->assertContains('create-ability', $permissionNames);
        $this->assertContains('update-ability', $permissionNames);
        $this->assertContains('delete-ability', $permissionNames);

        // Assert the proper guard is used
        $this->assertEquals('chief', Permission::first()->guard_name);
    }

    /** @test */
    public function permissions_can_be_assigned_to_a_role()
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
    public function permissions_can_be_assigned_to_multiple_roles()
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
