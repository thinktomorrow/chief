<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\App\Listeners\LogSuccessfulLogin;
use Thinktomorrow\Chief\Admin\Users\Application\EnableUser;
use Thinktomorrow\Chief\Admin\Users\Invites\Application\SendInvite;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\InviteAccepted;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Event::listen(
            Login::class,
            LogSuccessfulLogin::class,
        );

        Event::listen(
            UserInvited::class,
            SendInvite::class
        );

        Event::listen(
            InviteAccepted::class,
            EnableUser::class . '@onAcceptingInvite',
        );
    }
}
