<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Users\Invites\Application;

use Thinktomorrow\Chief\App\Notifications\InvitationMail;
use Thinktomorrow\Chief\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\Users\Invites\Invitation;
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
