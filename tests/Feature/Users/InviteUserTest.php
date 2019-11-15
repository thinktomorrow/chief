<?php

namespace Thinktomorrow\Chief\Tests\Feature\Users;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Users\User;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Users\Invites\InvitationState;
use Thinktomorrow\Chief\App\Notifications\InvitationMail;

class InviteUserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    public function only_admin_can_view_the_invite_form()
    {
        $response = $this->asAdmin()->get(route('chief.back.users.create'));
        $response->assertViewIs('chief::back.users.create')
                 ->assertStatus(200);
    }

    /** @test */
    public function regular_author_cannot_view_the_invite_form()
    {
        $response = $this->asAuthor()->get(route('chief.back.users.create'));

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.dashboard'))
            ->assertSessionHas('messages.error');
    }

    /** @test */
    public function inviting_a_new_user()
    {
        Notification::fake();

        $this->disableExceptionHandling();

        $response = $this->asAdmin()
                         ->post(route('chief.back.users.store'), $this->validParams());

        $response->assertStatus(302)
            ->assertRedirect(route('chief.back.users.index'))
            ->assertSessionHas('messages.success');

        $newUser = User::findByEmail('new@example.com');

        $this->assertNewValues($newUser);
        $this->assertEquals(InvitationState::PENDING, $newUser->invitation->last()->state());

        Notification::assertSentTo(new AnonymousNotifiable(), InvitationMail::class);
    }

    /** @test */
    public function it_can_render_the_invitation_mail()
    {
        $invitee = factory(User::class)->create();
        $inviter = $this->developer();

        $invitation = Invitation::make($invitee->id, $inviter->id);

        $this->verifyMailRender((new InvitationMail($invitation))->toMail('foobar@example.com'));
    }

    /** @test */
    public function only_authenticated_admin_can_invite_an_user()
    {
        $response = $this->post(route('chief.back.users.store'), $this->validParams());

        $response->assertRedirect(route('chief.back.login'));
        $this->assertCount(0, User::all());
    }

    /** @test */
    public function regular_author_cannot_invite_an_user()
    {
        $response = $this->asAuthor()->post(route('chief.back.users.store'), $this->validParams());

        $response->assertRedirect(route('chief.back.dashboard'));
        $this->assertCount(1, User::all()); // Existing author
    }

    /** @test */
    public function when_creating_user_firstname_is_required()
    {
        $this->assertValidation(new User(), 'firstname', $this->validParams(['firstname' => '']),
            route('chief.back.users.index'),
            route('chief.back.users.store'),
            1 // creating account (developer) already exists
        );
    }

    /** @test */
    public function when_creating_user_lastname_is_required()
    {
        $this->assertValidation(new User(), 'lastname', $this->validParams(['lastname' => '']),
            route('chief.back.users.index'),
            route('chief.back.users.store'),
            1 // creating account (developer) already exists
        );
    }

    /** @test */
    public function when_creating_user_role_is_required()
    {
        $this->assertValidation(new User(), 'roles', $this->validParams(['roles' => []]),
            route('chief.back.users.index'),
            route('chief.back.users.store'),
            1 // creating account (developer) already exists
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

    private function assertNewValues(User $user)
    {
        $this->assertEquals('new firstname', $user->firstname);
        $this->assertEquals('new lastname', $user->lastname);
        $this->assertEquals('new@example.com', $user->email);
        $this->assertEquals(['author'], $user->roles->pluck('name')->toArray());
    }
}
