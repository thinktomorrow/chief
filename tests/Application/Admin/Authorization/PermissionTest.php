<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Authorization;

use Thinktomorrow\Chief\Admin\Authorization\Permission;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class PermissionTest extends ChiefTestCase
{
    /** @test */
    public function an_user_can_be_checked_for_permission()
    {
        $admin = $this->fakeUser();

        $role = Role::create(['name' => 'superadmin']);
        Permission::create(['name' => 'create-article']);
        $role->givePermissionTo(['create-article']);

        $admin->assignRole('superadmin');

        $this->assertTrue($admin->can('create-article'));
        $this->assertFalse($admin->cant('create-article'));
    }

    /** @test */
    public function an_unknown_permission_does_not_authorize()
    {
        $admin = $this->fakeUser();

        $this->assertFalse($admin->can('unknown-permission'));
    }

    /** @test */
    public function an_user_can_have_multiple_roles()
    {
        $admin = $this->fakeUser();

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
