<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew;

use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-table-new', __DIR__ . '/UI/views');
    }
}
