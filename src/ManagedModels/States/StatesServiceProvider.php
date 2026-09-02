<?php

namespace Thinktomorrow\Chief\ManagedModels\States;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class StatesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-states', __DIR__.'/UI/views');

        Livewire::addNamespace(
            'chief-wire-states',
            classNamespace: 'Thinktomorrow\\Chief\\ManagedModels\\States\\UI\\Livewire',
            classPath: __DIR__.'/UI/Livewire',
            classViewPath: __DIR__.'/UI/views/livewire',
        );
    }

    public function register() {}
}
