<?php

namespace Thinktomorrow\Chief\App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Thinktomorrow\Chief\Admin\Authorization\ChiefUserProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::provider('chief-eloquent', function ($app, array $config) {
            return new ChiefUserProvider($app['hash'], $config['model']);
        });
    }
}
