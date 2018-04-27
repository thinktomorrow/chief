<?php

namespace Chief\Tests\Feature\Authorization;

use Chief\Authorization\Permission;
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

        $response = $this->actingAs(factory(User::class, 'admin')->create())->get(route('back.roles.create'));

        $response->assertStatus(302)->assertRedirect(route('back.dashboard'))->assertSessionHas('messages.error');
    }

    /** @test */
    function creating_a_new_article()
    {
        $this->disableExceptionHandling();

        $response = $this->actingAs(factory(User::class)->create())
            ->post(route('back.roles.store'), $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect(route('back.roles.index'));

        $this->assertCount(1, Role::all());
        $this->assertNewValues(Role::first());
    }

    /** @test */
    function only_authenticated_developer_can_create_a_article()
    {
        $response = $this->post(route('back.roles.store'), $this->validParams());

        $response->assertRedirect(route('back'));
        $this->assertCount(0, Role::all());
    }

    /** @test */
    function when_creating_role_name_is_required()
    {
        $this->assertValidation(new Role(), 'name', $this->validParams(['name' => '']),
            route('back.roles.index'),
            route('back.roles.store')
        );
    }

    private function validParams($overrides = [])
    {
        $params = [
            'name' => 'new name',
        ];

        foreach ($overrides as $key => $value){
            array_set($params,  $key, $value);
        }

        return $params;
    }

    private function assertNewValues($role)
    {
        $this->assertEquals('new name', $role->name);
    }
}