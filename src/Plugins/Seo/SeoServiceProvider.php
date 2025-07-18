<?php

namespace Thinktomorrow\Chief\Plugins\Seo;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class SeoServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-seo', __DIR__.'/UI/views');

        $this->loadPluginAdminRoutes(__DIR__.'/App/routes/chief-admin-routes.php');
    }

    public function register(): void
    {
        //
    }
}
