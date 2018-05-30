<?php

namespace Thinktomorrow\Chief\Tests\Feature\Users;

use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Users\Invites\Application\InviteUser;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Users\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

class AcceptInviteTest extends TestCase
{
    private $invitee;
    private $inviter;
    private $invitation;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->invitee = $this->developer();
        $this->inviter = $this->developer();

        $this->invitation = Invitation::make($this->invitee->id, $this->inviter->id);
        $this->invitation->changeState('pending');

        // Fake password so we can login with a known value
        $this->invitee->password = Hash::make('password');
        $this->invitee->save();
    }

    /** @test */
    public function signature_of_accept_url_is_validated()
    {
        // Manipulate the signature to mimic false request
        $parts = parse_url($this->invitation->acceptUrl());
        $url = str_replace($parts['query'], 'signature=fakesignature', $this->invitation->acceptUrl());

        $response = $this->get($url);

        $response->assertRedirect(route('invite.expired'));
    }

    /** @test */
    public function accept_url_with_invalid_token_is_declined()
    {
        // Manipulate the token but with valid signature
        $this->invitation->token = 'fake-token';
        $url = $this->invitation->acceptUrl();

        $response = $this->get($url);

        $response->assertRedirect(route('invite.expired'));
    }

    /** @test */
    public function accept_url_is_only_valid_when_used_before_expiration()
    {
        $this->invitation->expires_at = now()->subDays(4);
        $url = $this->invitation->acceptUrl();

        $response = $this->get($url);

        $response->assertRedirect(route('invite.expired'));
    }

    /** @test */
    public function accepting_invite_enables_user_account()
    {
        $this->assertFalse($this->invitee->isEnabled());
        $this->get($this->invitation->acceptUrl());

        $this->assertTrue($this->invitee->fresh()->isEnabled());

        $response = $this->post(route('chief.back.login.store'), [
            'email'    => $this->invitee->email,
            'password' => 'password',
        ]);
        $response->assertRedirect(route('chief.back.dashboard'));
    }

    /** @test */
    public function non_enabled_invitee_cannot_log_in()
    {
        $response = $this->post(route('chief.back.login.store'), [
            'email'    => $this->invitee->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
    }

    /** @test */
    public function accept_url_should_not_be_processed_when_invitation_is_revoked()
    {
        // Force invitation state on revoked
        $this->invitation->changeState('revoked');

        $response = $this->get($this->invitation->acceptUrl());
        $response->assertRedirect(route('invite.expired'));

        $this->assertFalse($this->invitee->fresh()->isEnabled());
    }

    /** @test */
    public function accept_url_logs_user_in_and_redirects_to_getting_started_page()
    {
        // Assert we are not yet logged in
        $this->assertFalse(auth()->guard('chief')->check());

        $response = $this->get($this->invitation->acceptUrl());

        $response->assertRedirect(route('chief.back.dashboard.getting-started'));

        // Assert we are logged in
        $this->assertEquals($this->invitee->id, auth()->guard('chief')->id());
    }

    /** @test */
    public function accept_url_redirects_user_to_password_edit_page_if_password_is_not_filled_in_yet()
    {
        Notification::fake();

        $invitee = new User();
        $invitee->email = 'email';
        $invitee->firstname = 'firstname';
        $invitee->lastname = 'lastname';
        $invitee->save();

        app(InviteUser::class)->handle($invitee, $this->inviter);
        $response = $this->get($invitee->fresh()->invitation->acceptUrl());
        $response->assertRedirect(route('chief.back.password.edit'));

        // Assert we are logged in
        $this->assertTrue(auth()->guard('chief')->check());
    }
}
