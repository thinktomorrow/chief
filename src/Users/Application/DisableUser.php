<?php

namespace Chief\Users\Application;

use Chief\Users\User;

class DisableUser
{
    public function handle(User $user)
    {
        $user->disable();
    }
}