<?php

namespace Thinktomorrow\Chief\Plugins\Docs;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class DocsServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/App/resources/views', 'chief-docs');

        $this->loadPluginAdminRoutes(__DIR__.'/App/chief-admin-routes.php');
    }
}
