<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\TableNew\UI\Livewire\TableComponent;

class TableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('chief-table-new-livewire::article-listing', TableComponent::class);

        $this->app['view']->addNamespace('chief-table-new', __DIR__ . '/UI/views');
    }
}
