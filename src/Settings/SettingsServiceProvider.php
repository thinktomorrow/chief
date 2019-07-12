<?php

namespace Thinktomorrow\Chief\Settings;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
    }

    public function register()
    {
        $this->app->singleton(Settings::class, function ($app) {
            return new Settings();
        });
    }
}
