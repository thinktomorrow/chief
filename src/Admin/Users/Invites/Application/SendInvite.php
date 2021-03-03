<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites\Application;

use Illuminate\Support\Facades\Notification;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;
use Thinktomorrow\Chief\App\Notifications\InvitationMail;

class SendInvite
{
    public function handle(UserInvited $event): void
    {
        $invitation = Invitation::findOrFail($event->invitation_id);

        Notification::route('mail', $invitation->invitee->email)
            ->notify(new InvitationMail($invitation));
    }
}
