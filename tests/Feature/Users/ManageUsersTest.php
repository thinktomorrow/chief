<?php

namespace Thinktomorrow\Chief\Tests\Feature\Users;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Users\User;

class ManageUsersTest extends TestCase
{
    private $newUser;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->newUser = new User();
        $this->newUser->email = 'email';
        $this->newUser->firstname = 'firstname';
        $this->newUser->lastname = 'lastname';
        $this->newUser->save();
    }

    /** @test */
    public function full_admin_can_view_the_users_overview()
    {
        $this->disableExceptionHandling();
        $response = $this->asAdmin()->get(route('chief.back.users.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function regular_author_cannot_view_the_users_overview()
    {
        $response = $this->asAuthor()->get(route('chief.back.users.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.dashboard'))
            ->assertSessionHas('messages.error');
    }
}
