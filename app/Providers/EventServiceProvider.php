<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Auth\Events\Login;
use Thinktomorrow\Chief\Site\Urls\Application\CreateUrlForPage;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Admin\Users\Application\EnableUser;
use Thinktomorrow\Chief\Admin\Users\Invites\Application\SendInvite;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\InviteAccepted;
use Thinktomorrow\Chief\Admin\Users\Invites\Events\UserInvited;
use Thinktomorrow\Chief\App\Listeners\LogSuccessfulLogin;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Event::listen(Login::class,LogSuccessfulLogin::class, );
        Event::listen(UserInvited::class,SendInvite::class);
        Event::listen(InviteAccepted::class,EnableUser::class . '@onAcceptingInvite');

        Event::listen(ManagedModelCreated::class,CreateUrlForPage::class . '@onManagedModelCreated');
    }
}
