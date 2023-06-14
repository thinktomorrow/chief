<?php

namespace Thinktomorrow\Chief\Plugins\HotSpots;

use Illuminate\Support\HtmlString;
use Livewire\Livewire;
use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class HotSpotsServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-hotspots', __DIR__ . '/views');

        Livewire::component('chief-wire::hotspots', HotSpotComponent::class);

        $this->app->make(ChiefPluginSections::class)
            ->addFooterSection(
                new HtmlString(view('chief-hotspots::footer'))
            )->addLivewireFileComponent('chief-wire::hotspots');



//        $this->loadPluginAdminRoutes(__DIR__ . '/App/routes/chief-admin-routes.php');
    }
}
