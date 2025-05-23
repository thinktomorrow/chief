<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Users;

use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ManageUsersTest extends ChiefTestCase
{
    private $newUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->newUser = new User;
        $this->newUser->email = 'email';
        $this->newUser->firstname = 'firstname';
        $this->newUser->lastname = 'lastname';
        $this->newUser->save();
    }

    public function test_full_admin_can_view_the_users_overview()
    {
        $response = $this->asAdmin()->get(route('chief.back.users.index'));
        $response->assertViewIs('chief::admin.users.index')
            ->assertStatus(200);
    }

    public function test_regular_author_cannot_view_the_users_overview()
    {
        $response = $this->asAuthor()->get(route('chief.back.users.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.dashboard'))
            ->assertSessionHas('messages.error');
    }
}
