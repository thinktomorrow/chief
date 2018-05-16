<?php

namespace Chief\Tests\Feature\Users;

use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;

class ManageUsersTest extends TestCase
{
    use ChiefDatabaseTransactions;

    private $newUser;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->setUpDefaultAuthorization();

        $this->newUser = new User();
        $this->newUser->email = 'email';
        $this->newUser->firstname = 'firstname';
        $this->newUser->lastname = 'lastname';
        $this->newUser->save();
    }

    /** @test */
    function full_admin_can_view_the_users_overview()
    {
        $this->disableExceptionHandling();
        $response = $this->asAdmin()->get(route('back.users.index'));
        $response->assertStatus(200);
    }

    /** @test */
    function regular_author_cannot_view_the_users_overview()
    {
        $response = $this->asAuthor()->get(route('back.users.index'));

        $response->assertStatus(302)
            ->assertRedirect(route('back.dashboard'))
            ->assertSessionHas('messages.error');
    }
}