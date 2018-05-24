<?php

namespace Thinktomorrow\Chief\App\Providers;

use Thinktomorrow\Chief\Users\Application\EnableUser;
use Thinktomorrow\Chief\Users\Invites\Application\SendInvite;
use Thinktomorrow\Chief\Users\Invites\Events\InviteAccepted;
use Thinktomorrow\Chief\Users\Invites\Events\UserInvited;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'Thinktomorrow\Chief\App\Listeners\LogSuccessfulLogin',
        ],

        UserInvited::class => [
            SendInvite::class
        ],
        InviteAccepted::class => [
            EnableUser::class.'@onAcceptingInvite',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
