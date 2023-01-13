<?php

namespace Thinktomorrow\Chief\App\Listeners;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event)
    {
        if ($event->guard !== 'chief') {
            return;
        }

        $event->user->last_login = date('Y-m-d H:i:s');
        $event->user->save();
    }
}
