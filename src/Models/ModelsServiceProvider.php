<?php

namespace Thinktomorrow\Chief\Models;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Models\UI\Livewire\CreateModelComponent;
use Thinktomorrow\Chief\Models\UI\Livewire\EditModelComponent;

class ModelsServiceProvider extends ServiceProvider
{
    public function boot(): void {}

    public function bootAdmin(): void
    {
        $this->app['view']->addNamespace('chief-models', __DIR__.'/UI/views');

        Livewire::component('chief-wire::create-model', CreateModelComponent::class);
        Livewire::component('chief-wire::edit-model', EditModelComponent::class);
    }
}
