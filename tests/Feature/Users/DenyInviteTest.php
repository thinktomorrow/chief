<?php

namespace Thinktomorrow\Chief\Tests\Feature\Users;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Users\Invites\InvitationState;
use Illuminate\Support\Facades\Hash;

class DenyInviteTest extends TestCase
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
    public function signature_of_deny_url_is_validated()
    {
        // Manipulate the signature to mimic false request
        $parts = parse_url($this->invitation->denyUrl());
        $url = str_replace($parts['query'], 'signature=fakesignature', $this->invitation->denyUrl());

        $response = $this->get($url);

        $response->assertRedirect(route('invite.expired'));
    }

    /** @test */
    public function deny_url_with_invalid_token_is_declined()
    {
        // Manipulate the token but with valid signature
        $this->invitation->token = 'fake-token';
        $url = $this->invitation->denyUrl();

        $response = $this->get($url);

        $response->assertRedirect(route('invite.expired'));
    }

    /** @test */
    public function deny_url_is_only_valid_when_used_before_expiration()
    {
        $this->invitation->expires_at = now()->subDays(4);
        $url = $this->invitation->denyUrl();

        $response = $this->get($url);

        $response->assertRedirect(route('invite.expired'));
    }

    /** @test */
    public function deny_url_should_not_be_processed_when_invitation_is_revoked()
    {
        // Force invitation state on revoked
        $this->invitation->changeState('revoked');

        $response = $this->get($this->invitation->denyUrl());
        $response->assertRedirect(route('invite.expired'));

        $this->assertFalse($this->invitee->fresh()->isEnabled());
    }

    /** @test */
    public function deny_url_sets_invitation_to_denied()
    {
        $this->disableExceptionHandling();
        $response = $this->get($this->invitation->denyUrl());

        $response->assertViewIs('chief::back.users.invite-denied');

        $this->assertEquals(InvitationState::DENIED, $this->invitation->fresh()->state());
        $this->assertFalse($this->invitee->fresh()->isEnabled());
    }
}
