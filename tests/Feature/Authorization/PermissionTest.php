<?php

namespace Chief\Tests\Feature\Authorization;

use Chief\Authorization\Permission;
use Chief\Authorization\Role;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;

class PermissionTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    /** @test */
    function an_user_can_be_checked_for_permission()
    {
        $admin = factory(User::class)->create();

        $role = Role::create(['name' => 'superadmin']);
        Permission::create(['name' => 'create-article']);
        $role->givePermissionTo(['create-article']);

        $admin->assignRole('superadmin');

        $this->assertTrue($admin->can('create-article'));
        $this->assertFalse($admin->cant('create-article'));
    }

    /** @test */
    function an_unknown_permission_does_not_authorize()
    {
        $admin = factory(User::class)->create();

        $this->assertFalse($admin->can('unknown-permission'));
    }

    /** @test */
    function an_user_can_have_multiple_roles()
    {
        $admin = factory(User::class)->create();

        Role::create(['name' => 'superadmin']);
        Role::create(['name' => 'editor']);

        $admin->assignRole('superadmin', 'editor');

        $this->assertCount(2, $admin->roles);
        $this->assertTrue($admin->hasRole('superadmin'));

        $this->assertFalse($admin->hasRole('unknown'));

        // If admin has at least one of these roles...
        $this->assertTrue($admin->hasAnyRole(['editor', 'unknown']));

        // If admin has all of the roles
        $this->assertFalse($admin->hasAllRoles(['editor', 'unknown']));

    }
}