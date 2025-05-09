<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable;

use Thinktomorrow\Chief\Plugins\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Read\TimeTableRead;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Read\TimeTableReadRepository;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models\DefaultTimeTableRead;
use Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Repositories\EloquentTimeTableReadRepository;

class TimeTableServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-timetable', __DIR__.'/App/resources/views');

        $this->loadMigrationsFrom(__DIR__.'/Infrastructure/migrations');

        $this->loadPluginAdminRoutes(__DIR__.'/App/routes/chief-admin-routes.php');
    }

    public function register()
    {
        $this->app->bind(TimeTableRead::class, fn () => DefaultTimeTableRead::class);

        $this->app->bind(TimeTableReadRepository::class, function ($app) {
            return $app->make(EloquentTimeTableReadRepository::class);
        });
    }
}
