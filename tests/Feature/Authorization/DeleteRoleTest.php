<?php

namespace Thinktomorrow\Chief\Tests\Feature\Authorization;

use Thinktomorrow\Chief\Authorization\Role;
use Thinktomorrow\Chief\Tests\TestCase;

class DeleteRoleTest extends TestCase
{
    private $newRole;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        // Create a new role first
        $role = Role::create(['name' => 'new name']);
        $role->givePermissionTo(['create-role', 'update-role']);
        $this->newRole = $role;
    }

    /** @test */
    public function deleting_a_new_role()
    {
        $response = $this->actingAs($this->developer(), 'chief')
            ->delete(route('chief.back.roles.destroy', $this->newRole->id));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.roles.index'))
            ->assertSessionHas('messages.success');

        $this->assertNull(Role::whereName('new name')->first());
        $this->assertDatabaseMissing('role_has_permissions', ['role_id' => $this->newRole->id]);
    }

    /** @test */
    public function only_authenticated_developer_can_delete_a_role()
    {
        $response = $this->asDefaultAdmin()
            ->delete(route('chief.back.roles.destroy', $this->newRole->id));

        $response->assertRedirect(route('chief.back.dashboard'));

        $this->assertNotNull(Role::whereName('new name')->first());
    }

    /** @test */
    public function role_is_deleted_for_connected_admin()
    {
        $developer = $this->developer();
        $developer->assignRole('new name');

        $this->assertNotNull($developer->roles()->whereName('new name')->first());

        $this->actingAs($this->developer(), 'chief')
            ->delete(route('chief.back.roles.destroy', $this->newRole->id));

        $this->assertNull($developer->roles()->whereName('new name')->first());
    }
}
