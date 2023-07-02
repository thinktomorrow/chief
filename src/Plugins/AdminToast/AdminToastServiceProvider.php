<?php

namespace Thinktomorrow\Chief\Plugins\AdminToast;

use Illuminate\Support\Facades\Blade;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class AdminToastServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        Blade::directive('chiefAdminToastMetatags', [AdminToastHTML::class, 'chiefAdminToastMetatags']);
        Blade::directive('chiefAdminToastScripts', [AdminToastHTML::class, 'chiefAdminToastScripts']);

        $this->app['view']->addNamespace('chief-admin-toast', __DIR__ . '/views');

        // These routes are loaded without admin middleware. They should be available on frontend.
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
