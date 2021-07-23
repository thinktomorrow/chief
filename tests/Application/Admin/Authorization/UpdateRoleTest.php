<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Authorization;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UpdateRoleTest extends ChiefTestCase
{
    private $newRole;

    public function setUp(): void
    {
        parent::setUp();

        // Create a new role first
        $role = Role::create(['name' => Arr::get($this->validParams(), 'name')]);
        $role->givePermissionTo(Arr::get($this->validParams(), 'permission_names'));
        $this->newRole = $role;
    }

    /** @test */
    public function only_developer_can_view_the_update_form()
    {
        $response = $this->actingAs($this->developer(), 'chief')->get(route('chief.back.roles.edit', Role::first()->id));
        $response->assertViewIs('chief::admin.authorization.roles.edit')
                 ->assertStatus(200);
    }

    /** @test */
    public function regular_admin_cannot_view_the_update_form()
    {
        $response = $this->actingAs($this->fakeUser(), 'chief')->get(route('chief.back.roles.edit', Role::first()->id));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.dashboard'))
            ->assertSessionHas('messages.error');
    }

    /** @test */
    public function updating_a_role()
    {
        // Now update it
        $response = $this->actingAs($this->developer(), 'chief')
            ->put(route('chief.back.roles.update', $this->newRole->id), $this->validUpdateParams());

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.roles.index'))
            ->assertSessionHas('messages.success');
        $this->assertUpdatedValues($this->newRole->fresh());
    }

    /** @test */
    public function only_authenticated_developer_can_update_a_role()
    {
        $response = $this->put(route('chief.back.roles.update', $this->newRole->id), $this->validUpdateParams());

        $response->assertRedirect(route('chief.back.login'));

        // Assert nothing was updated
        $this->assertNewValues($this->newRole->fresh());
    }

    /** @test */
    public function when_updating_role_name_is_required()
    {
        $this->assertValidation(new Role(), 'name', $this->validUpdateParams(['name' => '']), route('chief.back.roles.index'), route('chief.back.roles.update', $this->newRole->id), Role::count(), 'put');
    }

    /** @test */
    public function when_updating_role_permissions_are_required()
    {
        $this->assertValidation(new Role(), 'permission_names', $this->validParams(['permission_names' => '']), route('chief.back.roles.index'), route('chief.back.roles.update', $this->newRole->id), Role::count(), 'put');
    }

    /** @test */
    public function when_updating_role_name_must_be_unique()
    {
        $this->assertValidation(new Role(), 'name', $this->validParams(['name' => 'developer']), route('chief.back.roles.index'), route('chief.back.roles.update', $this->newRole->id), Role::count(), 'put');
    }

    /** @test */
    public function when_updating_role_permissions_must_be_passed_as_array()
    {
        $this->assertValidation(new Role(), 'permission_names', $this->validParams(['permission_names' => 'view-role']), route('chief.back.roles.index'), route('chief.back.roles.update', $this->newRole->id), Role::count(), 'put');
    }

    private function validParams($overrides = [])
    {
        $params = [
            'name' => 'new-name',
            'permission_names' => ['create-role', 'update-role'],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }

    private function validUpdateParams($overrides = [])
    {
        return $this->validParams(array_merge([
            'name' => 'updated-name',
            'permission_names' => ['view-role'],
        ], $overrides));
    }

    private function assertNewValues(Role $role)
    {
        $this->assertEquals('new-name', $role->name);
        $this->assertEquals(['create-role', 'update-role'], $role->permissions->pluck('name')->toArray());
    }

    private function assertUpdatedValues(Role $role)
    {
        $this->assertEquals('updated-name', $role->name);
        $this->assertEquals(['view-role'], $role->permissions->pluck('name')->toArray());
    }
}
