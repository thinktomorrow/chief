<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable;

use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\App\Providers\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\WeekTableRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\DateRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\WeekTableReadRepository;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\DefaultWeekTableRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models\DefaultDateRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Repositories\EloquentWeekTableReadRepository;

class WeekTableServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-weektable', __DIR__ . '/Admin/resources/views');

        $this->loadMigrationsFrom(__DIR__.'/Infrastructure/migrations');

        $this->loadPluginAdminRoutes(__DIR__.'/Admin/routes/chief-admin-routes.php');
    }

    public function register()
    {
        $this->app->bind(DateRead::class, fn () => DefaultDateRead::class);
        $this->app->bind(WeekTableRead::class, fn () => DefaultWeekTableRead::class);

        $this->app->bind(WeekTableReadRepository::class, function ($app) {
            return $app->make(EloquentWeekTableReadRepository::class);
        });
    }
}
