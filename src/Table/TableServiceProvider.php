<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Livewire\RowActionsDropdown;

class TableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-table', __DIR__ . '/resources');

        Livewire::component('chief-wire::row-actions-dropdown', RowActionsDropdown::class);
    }
}
