<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Settings;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    public function boot(): void
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
