<?php

namespace Chief\Users\Invites\Events;

class InviteAccepted
{
    public $invitation_id;

    public function __construct($invitation_id)
    {
        $this->invitation_id = $invitation_id;
    }
}