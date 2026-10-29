<?php

namespace Thinktomorrow\Chief\Plugins\Docs;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class DocsServiceProvider extends ChiefPluginServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->extend(DocSources::class, function (DocSources $sources) {
            $sources->add('chief', __DIR__.'/resources/docs');

            return $sources;
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/App/resources/views', 'chief-docs');

        $this->loadPluginAdminRoutes(__DIR__.'/App/chief-admin-routes.php');
    }
}
