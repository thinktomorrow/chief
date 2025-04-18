<?php

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Urls\UI\Livewire\Links\EditLinks;
use Thinktomorrow\Chief\Urls\UI\Livewire\Links\Links;

class UrlsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-urls', __DIR__.'/UI/views');

        // Site & link management for visitable model
        Livewire::component('chief-wire::links', Links::class);
        Livewire::component('chief-wire::edit-links', EditLinks::class);

    }

    public function bootAdmin(): void {}

    public function register() {}
}
