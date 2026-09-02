<?php

namespace Thinktomorrow\Chief\Menu;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Menu\App\Actions\ProjectModelData;
use Thinktomorrow\Chief\Menu\Events\MenuItemCreated;
use Thinktomorrow\Chief\Menu\Events\MenuItemUpdated;
use Thinktomorrow\Chief\Menu\Resources\DefaultMenuItemResource;
use Thinktomorrow\Chief\Menu\Resources\MenuItemResource;

class MenuServiceProvider extends ServiceProvider
{
    public function boot(): void {}

    public function bootAdmin(): void
    {
        $this->app['view']->addNamespace('chief-menu', __DIR__.'/UI/views');

        Livewire::addNamespace(
            'chief-wire-menu',
            classNamespace: 'Thinktomorrow\\Chief\\Menu\\UI\\Livewire',
            classPath: __DIR__.'/UI/Livewire',
            classViewPath: __DIR__.'/UI/views/livewire',
        );

        // Menu events
        Event::listen(MenuItemCreated::class, [ProjectModelData::class, 'onMenuItemCreated']);
        Event::listen(MenuItemUpdated::class, [ProjectModelData::class, 'onMenuItemUpdated']);
    }

    public function register(): void
    {
        Relation::morphMap(['menuitem' => MenuItem::class]);

        $this->app->bind(MenuItemResource::class, DefaultMenuItemResource::class);
    }
}
