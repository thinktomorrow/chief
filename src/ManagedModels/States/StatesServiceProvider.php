<?php

namespace Thinktomorrow\Chief\ManagedModels\States;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\ManagedModels\Listeners\TriggerPageChangedEvent;
use Thinktomorrow\Chief\ManagedModels\States\Events\ModelStateUpdated;
use Thinktomorrow\Chief\ManagedModels\States\Listeners\DispatchChiefActionCompleted;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\EditState;
use Thinktomorrow\Chief\ManagedModels\States\UI\Livewire\State;

class StatesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(ModelStateUpdated::class, [TriggerPageChangedEvent::class, 'onModelStateUpdated']);
        Event::listen(ModelStateUpdated::class, [DispatchChiefActionCompleted::class, 'handle']);

        $this->app['view']->addNamespace('chief-states', __DIR__.'/UI/views');

        Livewire::component('chief-wire::state', State::class);
        Livewire::component('chief-wire::edit-state', EditState::class);
    }

    public function register() {}
}
