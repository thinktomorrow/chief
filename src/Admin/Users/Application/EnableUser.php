<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Application;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\InviteAccepted;
use Thinktomorrow\Chief\Admin\Users\Invites\Invitation;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\ManagedModels\Events\ChiefActionCompleted;

class EnableUser
{
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->enable();
            event(ChiefActionCompleted::forModels('user.enabled', $user));
        });
    }

    public function onAcceptingInvite(InviteAccepted $event): void
    {
        $invitation = Invitation::find($event->invitation_id);

        $this->handle($invitation->invitee);
    }
}
