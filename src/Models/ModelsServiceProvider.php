<?php

namespace Thinktomorrow\Chief\Models;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class ModelsServiceProvider extends ServiceProvider
{
    public function boot(): void {}

    public function bootAdmin(): void
    {
        $this->app['view']->addNamespace('chief-models', __DIR__.'/UI/views');

        Livewire::addNamespace(
            'chief-wire-models',
            classNamespace: 'Thinktomorrow\\Chief\\Models\\UI\\Livewire',
            classPath: __DIR__.'/UI/Livewire',
            classViewPath: __DIR__.'/UI/views/livewire',
        );
    }
}
