<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Authorization;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Admin\Authorization\AuthorizationDefaults;
use Thinktomorrow\Chief\Admin\Authorization\Role;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class CreateRoleTest extends ChiefTestCase
{
    public function test_only_developer_can_view_the_create_form()
    {
        $this->disableExceptionHandling();
        $developer = $this->fakeUser();
        $developer->assignRole('developer');

        $response = $this->actingAs($developer, 'chief')->get(route('chief.back.roles.create'));
        $response->assertViewIs('chief::admin.authorization.roles.create')
            ->assertStatus(200);
    }

    public function test_regular_admin_cannot_view_the_create_form()
    {
        $response = $this->asAdminWithoutRole()->get(route('chief.back.roles.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.dashboard'))
            ->assertSessionHas('messages.error');
    }

    public function test_storing_a_new_role()
    {
        $response = $this->actingAs($this->developer(), 'chief')
            ->post(route('chief.back.roles.store'), $this->validParams());

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.roles.index'))
            ->assertSessionHas('messages.success');

        $this->assertNewValues(Role::findByName('new name'));
    }

    public function test_only_authenticated_developer_can_store_a_role()
    {
        $response = $this->post(route('chief.back.roles.store'), $this->validParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(AuthorizationDefaults::roles()->count(), Role::all()); // default roles were already present
    }

    public function test_when_creating_role_name_is_required()
    {
        $this->assertValidation(
            new Role,
            'name',
            $this->validParams(['name' => '']),
            route('chief.back.roles.index'),
            route('chief.back.roles.store'),
            AuthorizationDefaults::roles()->count() // default roles were already present
        );
    }

    public function test_when_creating_role_name_must_be_unique()
    {
        $this->assertValidation(
            new Role,
            'name',
            $this->validParams(['name' => 'developer']),
            route('chief.back.roles.index'),
            route('chief.back.roles.store'),
            AuthorizationDefaults::roles()->count() // default roles were already present
        );
    }

    public function test_when_creating_role_permissions_are_required()
    {
        $this->assertValidation(
            new Role,
            'permission_names',
            $this->validParams(['permission_names' => '']),
            route('chief.back.roles.index'),
            route('chief.back.roles.store'),
            AuthorizationDefaults::roles()->count() // default roles were already present
        );
    }

    public function test_when_creating_role_permissions_must_be_passed_as_array()
    {
        $this->assertValidation(
            new Role,
            'permission_names',
            $this->validParams(['permission_names' => 'view-role']),
            route('chief.back.roles.index'),
            route('chief.back.roles.store'),
            AuthorizationDefaults::roles()->count() // default roles were already present
        );
    }

    private function validParams($overrides = [])
    {
        $params = [
            'name' => 'new name',
            'permission_names' => ['create-role', 'update-role'],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }

    private function assertNewValues(Role $role)
    {
        $this->assertEquals('new name', $role->name);
        $this->assertEquals(['create-role', 'update-role'], $role->permissions->pluck('name')->toArray());
    }
}
