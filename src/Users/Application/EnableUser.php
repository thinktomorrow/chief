<?php

namespace Thinktomorrow\Chief\Users\Application;

use Thinktomorrow\Chief\Users\Invites\Events\InviteAccepted;
use Thinktomorrow\Chief\Users\Invites\Invitation;
use Thinktomorrow\Chief\Users\User;

class EnableUser
{
    public function handle(User $user)
    {
        $user->enable();
    }

    public function onAcceptingInvite(InviteAccepted $event)
    {
        $invitation = Invitation::find($event->invitation_id);

        $this->handle($invitation->invitee);
    }
}