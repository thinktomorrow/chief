<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites;

use Thinktomorrow\Chief\ManagedModels\States\State\State;

enum InvitationState: string implements State
{
    const KEY = 'state';

    /**
     * Possible states of the invitation process:
     *
     * NONE - no invitation sent; nothing has happened so far
     * PENDING - invitation sent or resent and awaiting acceptance
     * EXPIRED - invitation is no longer valid due to the time restriction
     * ACCEPTED - new user has accepted the invite
     * DENIED - new user has denied the invite explicitly
     * REVOKED - invitation is revoked by an admin
     */
    case none = 'none';
    case pending = 'pending';
    case expired = 'expired';
    case accepted = 'accepted';
    case denied = 'denied';
    case revoked = 'revoked';

    public function getValueAsString(): string
    {
        return $this->value;
    }
}
