<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Users;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UpdateUserTest extends ChiefTestCase
{
    private $newUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->newUser = new User();
        $this->newUser->email = 'new@example.com';
        $this->newUser->firstname = 'new firstname';
        $this->newUser->lastname = 'new lastname';
        $this->newUser->save();
        $this->newUser->assignRole('author');
    }

    /** @test */
    public function full_admin_can_see_the_edit_view()
    {
        $response = $this->asAdmin()->get(route('chief.back.users.edit', $this->newUser->id));
        $response->assertViewIs('chief::back.users.edit')
                 ->assertStatus(200);
    }

    /** @test */
    public function regular_author_cannot_view_the_edit_view()
    {
        $response = $this->asAuthor()->get(route('chief.back.users.edit', $this->newUser->id));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.dashboard'))
            ->assertSessionHas('messages.error');
    }

    /** @test */
    public function updating_user_data()
    {
        // Now update it
        $response = $this->asAdmin()
            ->put(route('chief.back.users.update', $this->newUser->id), $this->validUpdateParams());

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.users.index'))
            ->assertSessionHas('messages.success');

        $this->assertUpdatedValues($this->newUser->fresh());
    }

    /** @test */
    public function only_authenticated_admin_can_update_a_user()
    {
        $response = $this->put(route('chief.back.users.update', $this->newUser->id), $this->validUpdateParams());

        $response->assertRedirect(route('chief.back.login'));

        // Assert nothing was updated
        $this->assertNewValues($this->newUser->fresh());
    }

    /** @test */
    public function regular_author_cannot_update_any_user()
    {
        $response = $this->asAuthor()->put(route('chief.back.users.update', $this->newUser->id), $this->validUpdateParams());

        $response->assertRedirect(route('chief.back.dashboard'));

        // Assert nothing was updated
        $this->assertNewValues($this->newUser->fresh());
    }

//    /** @test */
    public function non_developer_cannot_update_an_existing_developer()
    {
        $this->newUser->assignRole('developer');

        $response = $this->asAdmin()->put(route('chief.back.users.update', $this->newUser->id), $this->validUpdateParams([
            'roles' => ['admin'],
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.users.index'))
            ->assertSessionHas('messages.success');

        // Assert roles were not updated
        $this->assertEquals(['author','developer'], $this->newUser->fresh()->roles->pluck('name')->toArray());
    }

    /** @test */
    public function only_developer_can_give_developer_role_to_user()
    {
        $response = $this->asDeveloper()->put(route('chief.back.users.update', $this->newUser->id), $this->validUpdateParams([
            'roles' => ['developer'],
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.users.index'))
            ->assertSessionHas('messages.success');

        $this->assertEquals(['developer'], $this->newUser->fresh()->roles->pluck('name')->toArray());
    }

    /** @test */
    public function non_developer_cannot_give_developer_role_to_user()
    {
        $response = $this->asAdmin()->put(route('chief.back.users.update', $this->newUser->id), $this->validUpdateParams([
            'roles' => ['developer'],
        ]));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.dashboard'))
            ->assertSessionHas('messages.error');

        // Assert nothing was updated
        $this->assertNewValues($this->newUser->fresh());
    }

    /** @test */
    public function when_updating_user_firstname_is_required()
    {
        $this->assertValidation(new User(), 'firstname', $this->validUpdateParams(['firstname' => '']),
            route('chief.back.users.index'),
            route('chief.back.users.update', $this->newUser->id),
            2, // Admin self and existing one
            'put'
        );
    }

    /** @test */
    public function when_updating_user_lastname_is_required()
    {
        $this->assertValidation(new User(), 'lastname', $this->validUpdateParams(['lastname' => '']),
            route('chief.back.users.index'),
            route('chief.back.users.update', $this->newUser->id),
            2, // Admin self and existing one
            'put'
        );
    }

    private function validParams($overrides = [])
    {
        $params = [
            'firstname' => 'new firstname',
            'lastname' => 'new lastname',
            'email' => 'new@example.com',
            'roles' => ['author'],
        ];

        foreach ($overrides as $key => $value) {
            Arr::set($params, $key, $value);
        }

        return $params;
    }

    private function validUpdateParams($overrides = [])
    {
        return $this->validParams(array_merge([
            'firstname' => 'updated firstname',
            'lastname' => 'updated lastname',
            'email' => 'updated@example.com',
            'roles' => ['author', 'admin'],
        ], $overrides));
    }

    private function assertNewValues(User $user)
    {
        $this->assertEquals('new firstname', $user->firstname);
        $this->assertEquals('new lastname', $user->lastname);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals(['author'], $user->roles->pluck('name')->toArray());
    }

    private function assertUpdatedValues(User $user)
    {
        $this->assertEquals('updated firstname', $user->firstname);
        $this->assertEquals('updated lastname', $user->lastname);
        $this->assertEquals('updated@example.com', $user->email);
        $this->assertEquals(['author','admin'], $user->roles->pluck('name')->toArray());
    }
}
