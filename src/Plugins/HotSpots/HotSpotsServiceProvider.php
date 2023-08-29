<?php

namespace Thinktomorrow\Chief\Plugins\HotSpots;

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
            ->addLivewireFileComponent('chief-wire::hotspots')
            ->addLivewireFileEditAction('chief-hotspots::file-edit-action');
    }
}
