<?php

namespace Thinktomorrow\Chief\Fragments;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Fragments\App\Components\Fragments;
use Thinktomorrow\Chief\Fragments\App\Components\SidebarFragment;

class FragmentsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-fragments', __DIR__ . '/App/views');

        Blade::component(SidebarFragment::class, 'chief-fragments::sidebar');
        Blade::component(Fragments::class, 'chief-fragments::index');
    }
}
