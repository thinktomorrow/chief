<?php

namespace Thinktomorrow\Chief\Sites;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Sites\UI\Livewire\ResourceSites;

class SitesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-sites', __DIR__ . '/UI/views');

        Livewire::component('chief-wire::resource-sites', ResourceSites::class);
    }

    public function register()
    {

    }
}
