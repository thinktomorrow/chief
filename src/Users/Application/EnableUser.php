<?php

namespace Chief\Users\Application;

use Chief\Users\Invites\Events\InviteAccepted;
use Chief\Users\Invites\Invitation;
use Chief\Users\User;

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