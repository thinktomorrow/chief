<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Users;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UpdateYouTest extends ChiefTestCase
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
    public function only_you_can_see_the_edit_view()
    {
        $response = $this->asAdmin()->get(route('chief.back.you.edit'));

        $this->assertNotEquals($this->newUser->id, $response->getOriginalContent()->getData()['user']->id);
    }

    /** @test */
    public function updating_your_data()
    {
        $response = $this->actingAs($this->newUser, 'chief')
            ->put(route('chief.back.you.update'), $this->validUpdateParams());

        $response->assertStatus(302)
            ->assertSessionHas('messages.success');

        $this->assertUpdatedValues($this->newUser->fresh());
    }

    /** @test */
    public function only_authenticated_admin_can_update_their_profile()
    {
        $response = $this->put(route('chief.back.you.update'), $this->validUpdateParams());

        $response->assertRedirect(route('chief.back.login'));

        // Assert nothing was updated
        $this->assertNewValues($this->newUser->fresh());
    }

    /** @test */
    public function when_updating_user_firstname_is_required()
    {
        $this->assertValidation(new User(), 'firstname', $this->validUpdateParams(['firstname' => '']),
            route('chief.back.dashboard'),
            route('chief.back.you.update'),
            2, // Admin self and existing one
            'put'
        );
    }

    /** @test */
    public function when_updating_user_lastname_is_required()
    {
        $this->assertValidation(new User(), 'lastname', $this->validUpdateParams(['lastname' => '']),
            route('chief.back.dashboard'),
            route('chief.back.you.update'),
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
        ], $overrides));
    }

    private function assertNewValues(User $user)
    {
        $this->assertEquals('new firstname', $user->firstname);
        $this->assertEquals('new lastname', $user->lastname);
        $this->assertEquals('new@example.com', $user->email);
    }

    private function assertUpdatedValues(User $user)
    {
        $this->assertEquals('updated firstname', $user->firstname);
        $this->assertEquals('updated lastname', $user->lastname);
        $this->assertEquals('updated@example.com', $user->email);
    }
}
