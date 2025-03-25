<?php

namespace Thinktomorrow\Chief\Menu;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Menu\App\Actions\ProjectModelData;
use Thinktomorrow\Chief\Menu\Events\MenuItemCreated;
use Thinktomorrow\Chief\Menu\Events\MenuItemUpdated;
use Thinktomorrow\Chief\Menu\UI\Livewire\EditMenus;
use Thinktomorrow\Chief\Menu\UI\Livewire\Menus;

class MenuServiceProvider extends ServiceProvider
{
    public function boot(): void {}

    public function bootAdmin(): void
    {
        $this->app['view']->addNamespace('chief-menu', __DIR__.'/UI/views');

        Livewire::component('chief-wire::menus', Menus::class);
        Livewire::component('chief-wire::edit-menus', EditMenus::class);

        // Menu events
        Event::listen(MenuItemCreated::class, [ProjectModelData::class, 'onMenuItemCreated']);
        Event::listen(MenuItemUpdated::class, [ProjectModelData::class, 'onMenuItemUpdated']);
    }

    public function register(): void
    {
        Relation::morphMap(['menuitem' => MenuItem::class]);
    }
}
