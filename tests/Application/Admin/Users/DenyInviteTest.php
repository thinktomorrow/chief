<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Users;

use Illuminate\Support\Facades\Hash;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;
use Thinktomorrow\Chief\Admin\Users\Invites\InvitationState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class DenyInviteTest extends ChiefTestCase
{
    private $invitee;

    private $inviter;

    private $invitation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->invitee = $this->developer();
        $this->inviter = $this->developer();

        $this->invitation = Invitation::make($this->invitee->id, $this->inviter->id);
        $this->invitation->changeState(InvitationState::KEY, InvitationState::pending);

        // Fake password so we can login with a known value
        $this->invitee->password = Hash::make('password');
        $this->invitee->save();
    }

    public function test_signature_of_deny_url_is_validated()
    {
        // Manipulate the signature to mimic false request
        $parts = parse_url($this->invitation->denyUrl());
        $url = str_replace($parts['query'], 'signature=fakesignature', $this->invitation->denyUrl());

        $response = $this->get($url);

        $response->assertRedirect(route('invite.expired'));
    }

    public function test_deny_url_with_invalid_token_is_declined()
    {
        // Manipulate the token but with valid signature
        $this->invitation->token = 'fake-token';
        $url = $this->invitation->denyUrl();

        $response = $this->get($url);

        $response->assertRedirect(route('invite.expired'));
    }

    public function test_deny_url_is_only_valid_when_used_before_expiration()
    {
        $this->invitation->expires_at = now()->subDays(4);
        $url = $this->invitation->denyUrl();

        $response = $this->get($url);

        $response->assertRedirect(route('invite.expired'));
    }

    public function test_deny_url_should_not_be_processed_when_invitation_is_revoked()
    {
        // Force invitation state on revoked
        $this->invitation->changeState(InvitationState::KEY, InvitationState::revoked);

        $response = $this->get($this->invitation->denyUrl());
        $response->assertRedirect(route('invite.expired'));

        $this->assertFalse($this->invitee->fresh()->isEnabled());
    }

    public function test_deny_url_sets_invitation_to_denied()
    {
        $response = $this->get($this->invitation->denyUrl());

        $response->assertViewIs('chief::admin.users.invite-denied');

        $this->assertEquals(InvitationState::denied, $this->invitation->fresh()->getState(InvitationState::KEY));
        $this->assertFalse($this->invitee->fresh()->isEnabled());
    }
}
