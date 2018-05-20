<?php

namespace App\Providers;

use Chief\Users\Application\EnableUser;
use Chief\Users\Invites\Application\SendInvite;
use Chief\Users\Invites\Events\InviteAccepted;
use Chief\Users\Invites\Events\UserInvited;
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
            'App\Listeners\LogSuccessfulLogin',
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
