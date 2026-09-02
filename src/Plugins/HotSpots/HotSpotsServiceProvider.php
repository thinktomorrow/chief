<?php

namespace Thinktomorrow\Chief\Plugins\HotSpots;

use Livewire\Livewire;
use Thinktomorrow\Chief\Plugins\ChiefPluginSections;
use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;

class HotSpotsServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-hotspots', __DIR__.'/UI/views');

        Livewire::addNamespace(
            namespace: 'chief-wire-hotspots',
            classNamespace: 'Thinktomorrow\\Chief\\Plugins\\HotSpots\\UI\\Livewire',
            classPath: __DIR__.'/UI/Livewire',
            classViewPath: __DIR__.'/UI/views/livewire',
        );

        $this->app->make(ChiefPluginSections::class)
            ->addLivewireFileComponent('chief-wire-hotspots::hot-spot-editor')
            ->addLivewireFileEditAction('chief-hotspots::file-edit-action');
    }
}
