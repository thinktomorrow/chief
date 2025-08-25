<?php

namespace Thinktomorrow\Chief\Plugins\Hive;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class HiveServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-hive', __DIR__.'/UI/views');

        $this->mergeConfigFrom(__DIR__.'/config/chief-hive.php', 'chief-hive');

        $this->loadPluginAdminRoutes(__DIR__.'/App/routes/chief-admin-routes.php');

        $this->publishes([
            __DIR__.'/config/chief-hive.php' => config_path('chief-hive.php'),
        ], 'chief-hive-config');
    }

    public function register(): void
    {
        //
    }
}
