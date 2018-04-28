<?php

namespace Chief\Tests\Feature\Authorization;

use Chief\Authorization\AuthorizationDefaults;
use Chief\Authorization\Permission;
use Chief\Authorization\Role;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;

class DeleteRoleTest extends TestCase
{
    use ChiefDatabaseTransactions;

    private $newRole;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->setUpDefaultAuthorization();

        // Create a new role first
        $role = Role::create(['name' => 'new name']);
        $role->givePermissionTo(['create-role', 'update-role']);
        $this->newRole = $role;
    }

    /** @test */
    function deleting_a_new_role()
    {
        $response = $this->actingAs($this->developer(), 'admin')
            ->delete(route('back.roles.destroy', $this->newRole->id));

        $response->assertStatus(302)
            ->assertRedirect(route('back.roles.index'))
            ->assertSessionHas('messages.success');

        $this->assertNull(Role::whereName('new name')->first());
        $this->assertDatabaseMissing('role_has_permissions',['role_id' => $this->newRole->id]);
    }

    /** @test */
    function only_authenticated_developer_can_delete_a_role()
    {
        $response = $this->actingAs(factory(User::class)->create(), 'admin')
            ->delete(route('back.roles.destroy', $this->newRole->id));

        $response->assertRedirect(route('back.dashboard'));

        $this->assertNotNull(Role::whereName('new name')->first());
    }

    /** @test */
    function role_is_deleted_for_connected_admin()
    {
        $developer = $this->developer();
        $developer->assignRole('new name');

        $this->assertNotNull($developer->roles()->whereName('new name')->first());

        $this->actingAs($this->developer(), 'admin')
            ->delete(route('back.roles.destroy', $this->newRole->id));

        $this->assertNull($developer->roles()->whereName('new name')->first());
    }
}