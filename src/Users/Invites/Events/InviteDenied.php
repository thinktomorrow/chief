<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Users\Invites\Events;

class InviteDenied
{
    public $invitation_id;

    public function __construct($invitation_id)
    {
        $this->invitation_id = $invitation_id;
    }
}
