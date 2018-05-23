<?php

namespace Chief\Tests\Feature\Users;

use Chief\Authorization\Role;
use Chief\Tests\ChiefDatabaseTransactions;
use Chief\Tests\TestCase;
use Chief\Users\User;

class UpdateYouTest extends TestCase
{
    use ChiefDatabaseTransactions;

    private $newUser;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->setUpDefaultAuthorization();

        $this->newUser = new User();
        $this->newUser->email = 'new@example.com';
        $this->newUser->firstname = 'new firstname';
        $this->newUser->lastname = 'new lastname';
        $this->newUser->save();
        $this->newUser->assignRole('author');
    }

    /** @test */
    function only_you_can_see_the_edit_view()
    {
        $this->disableExceptionHandling();

        $response = $this->asAdmin()->get(route('back.you.edit'));

        $this->assertNotEquals($this->newUser->id, $response->getOriginalContent()->getData()['user']->id);
    }

    /** @test */
    function updating_your_data()
    {
        $response = $this->actingAs($this->newUser, 'admin')
            ->put(route('back.you.update'), $this->validUpdateParams());

        $response->assertStatus(302)
            ->assertSessionHas('messages.success');

        $this->assertUpdatedValues($this->newUser->fresh());
    }

    /** @test */
    function only_authenticated_admin_can_update_their_profile()
    {
        $response = $this->put(route('back.you.update'), $this->validUpdateParams());

        $response->assertRedirect(route('back.login'));

        // Assert nothing was updated
        $this->assertNewValues($this->newUser->fresh());
    }

    /** @test */
    function when_email_is_changed_a_notification_is_sent_to_the_new_and_old_email()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    function when_updating_user_firstname_is_required()
    {
        $this->assertValidation(new User(), 'firstname', $this->validUpdateParams(['firstname' => '']),
            route('back.dashboard'),
            route('back.you.update'),
            2, // Admin self and existing one
            'put'
        );
    }

    /** @test */
    function when_updating_user_lastname_is_required()
    {
        $this->assertValidation(new User(), 'lastname', $this->validUpdateParams(['lastname' => '']),
            route('back.dashboard'),
            route('back.you.update'),
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

        foreach ($overrides as $key => $value){
            array_set($params,  $key, $value);
        }

        return $params;
    }

    private function validUpdateParams($overrides = [])
    {
        return $this->validParams(array_merge([
            'firstname' => 'updated firstname',
            'lastname' => 'updated lastname',
            'email' => 'updated@example.com',
        ],$overrides));
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