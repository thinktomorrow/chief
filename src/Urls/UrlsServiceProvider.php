<?php

namespace Thinktomorrow\Chief\Urls;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class UrlsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-urls', __DIR__.'/UI/views');

        Livewire::addNamespace(
            'chief-wire-urls',
            classNamespace: 'Thinktomorrow\\Chief\\Urls\\UI\\Livewire',
            classPath: __DIR__.'/UI/Livewire',
            classViewPath: __DIR__.'/UI/views/livewire',
        );
    }

    public function bootAdmin(): void {}

    public function register() {}
}
