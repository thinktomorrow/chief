<?php

namespace Chief\Users\Invites\Application;

use App\Notifications\InvitationMail;
use Chief\Users\Invites\Events\UserInvited;
use Chief\Users\Invites\Invitation;
use Illuminate\Support\Facades\Notification;

class SendInvite
{
    public function handle(UserInvited $event)
    {
        $invitation = Invitation::findOrFail($event->invitation_id);

        Notification::route('mail', $invitation->invitee->email)
            ->notify(new InvitationMail($invitation));
    }
}