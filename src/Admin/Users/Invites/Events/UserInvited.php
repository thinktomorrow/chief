<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Users\Invites\Events;

class UserInvited
{
    public $invitation_id;

    public function __construct($invitation_id)
    {
        $this->invitation_id = $invitation_id;
    }
}
