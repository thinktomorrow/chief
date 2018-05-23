<?php

namespace Thinktomorrow\Chief\App\Providers;

use Chief\Authorization\ChiefUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('chief-eloquent',function($app, array $config){
            return new ChiefUserProvider($app['hash'], $config['model']);
        });
    }
}
