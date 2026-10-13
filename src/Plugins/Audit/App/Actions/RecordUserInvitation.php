<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Actions;

use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\Plugins\Audit\History;

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
            context: ['invitee_email' => $event->inviteeEmail, 'invitation_id' => (string) $event->invitation_id],
        );
    }
}
