<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Application;

use Thinktomorrow\Chief\Admin\Users\Invites\Events\InviteAccepted;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;
use Thinktomorrow\Chief\Admin\Users\User;

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
