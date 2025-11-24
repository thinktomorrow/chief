<?php

namespace Thinktomorrow\Chief\Plugins\Docs;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class DocsServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-docs', __DIR__.'/App/resources/views');

        $this->loadPluginAdminRoutes(__DIR__.'/App/chief-admin-routes.php');
    }
}
