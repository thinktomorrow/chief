<?php

namespace Chief\Tests\Feature\Authorization;

use Chief\Authorization\Role;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;

class CreateRoleTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->artisan('chief:permission', ['name' => 'role']);
        $this->artisan('chief:permission', ['name' => 'permission']);
        $this->artisan('chief:role', ['name' => 'developer', '--permissions' => 'role,permission']);
    }

    /** @test */
    function only_developer_can_view_the_create_form()
    {
        $developer = factory(User::class)->create();
        $developer->assignRole('developer');

        $response = $this->actingAs($developer, 'admin')->get(route('back.roles.create'));
        $response->assertStatus(200);
    }

    /** @test */
    function regular_admin_cannot_view_the_create_form()
    {
        $this->disableExceptionHandling();

        $response = $this->actingAs(factory(User::class)->create(), 'admin')->get(route('back.roles.create'));

        $response->assertStatus(302)
                 ->assertRedirect(route('back.dashboard'))
                 ->assertSessionHas('messages.error');
    }

    /** @test */
    function storing_a_new_role()
    {
        $developer = factory(User::class)->create();
        $developer->assignRole('developer');

        $response = $this->actingAs($developer, 'admin')
            ->post(route('back.roles.store'), $this->validParams());

        $response->assertStatus(302)
                 ->assertRedirect(route('back.roles.index'))
                 ->assertSessionHas('messages.success');

        $this->assertNewValues(Role::findByName('new name'));
    }

    /** @test */
    function only_authenticated_developer_can_store_a_role()
    {
        $response = $this->post(route('back.roles.store'), $this->validParams());

        $response->assertRedirect(route('back.login'));
        $this->assertCount(1, Role::all()); // Developer role was already present
    }

    /** @test */
    function when_creating_role_name_is_required()
    {
        $this->assertValidation(new Role(), 'name', $this->validParams(['name' => '']),
            route('back.roles.index'),
            route('back.roles.store'),
            1 // Developer role was already present
        );
    }

    /** @test */
    function when_creating_role_permissions_are_required()
    {
        $this->assertValidation(new Role(), 'permission_names', $this->validParams(['permission_names' => '']),
            route('back.roles.index'),
            route('back.roles.store'),
            1 // Developer role was already present
        );
    }

    /** @test */
    function when_creating_role_permissions_must_be_passed_as_array()
    {
        $this->assertValidation(new Role(), 'permission_names', $this->validParams(['permission_names' => 'view-role']),
            route('back.roles.index'),
            route('back.roles.store'),
            1 // Developer role was already present
        );
    }

    private function validParams($overrides = [])
    {
        $params = [
            'name' => 'new name',
            'permission_names' => ['create-role', 'update-role'],
        ];

        foreach ($overrides as $key => $value){
            array_set($params,  $key, $value);
        }

        return $params;
    }

    private function assertNewValues(Role $role)
    {
        $this->assertEquals('new name', $role->name);
        $this->assertEquals(['create-role', 'update-role'], $role->permissions->pluck('name')->toArray());
    }
}