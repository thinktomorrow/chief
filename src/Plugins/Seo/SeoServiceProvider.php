<?php

namespace Thinktomorrow\Chief\Plugins\Seo;

use Livewire\Livewire;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\Seo\UI\Livewire\EditAltComponent;

class SeoServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-seo', __DIR__.'/UI/views');

        $this->loadPluginAdminRoutes(__DIR__.'/App/routes/chief-admin-routes.php');

        Livewire::component('chief-wire::edit-alt', EditAltComponent::class);
    }

    public function register(): void
    {
        //
    }
}
