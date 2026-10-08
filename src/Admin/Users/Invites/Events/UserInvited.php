<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites\Events;

class UserInvited
{
    public $invitation_id;

    /** @param array{name: string, id: string} $actorSnapshot */
    public function __construct($invitation_id, public array $actorSnapshot, public string $inviteeEmail, public string $occurredAt)
    {
        $this->invitation_id = $invitation_id;
    }
}
