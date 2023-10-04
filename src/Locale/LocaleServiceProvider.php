<?php

namespace Thinktomorrow\Chief\Locale;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Locale\App\Livewire\ModelLocales;

class LocaleServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-locale', __DIR__ . '/App/views');

        Livewire::component('chief-wire::model-locales', ModelLocales::class);
    }

    public function register()
    {

    }
}
