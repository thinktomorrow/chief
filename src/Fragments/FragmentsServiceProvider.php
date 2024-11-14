<?php

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FragmentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-fragments', __DIR__ . '/resources');

        Blade::component('chief-fragments::components.sidebar-fragment', 'chief-fragments::sidebar-fragment');
    }
}
