<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table;

use Illuminate\Support\ServiceProvider;

class TableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-table', __DIR__ . '/resources');
    }
}
