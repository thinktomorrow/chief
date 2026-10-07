<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;

final class RecordUserInvitation
{
    public function handle(UserInvited $event): void
    {
        History::log(
            type: 'chief.user.invited',
            actorType: 'admin',
            actorSnapshot: $event->actorSnapshot,
            occurredAt: $event->occurredAt,
            category: 'users',
            outcome: 'success',
            summary: 'User invited',
            context: ['invitee_email' => $event->inviteeEmail],
        );
    }
}
