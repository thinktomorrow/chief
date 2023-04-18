<?php

namespace Thinktomorrow\Chief\Plugins\Tags;

use Thinktomorrow\Chief\App\Providers\ChiefPluginServiceProvider;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagGroupRead;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\Application\Taggable\TaggableRepository;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models\DefaultTagGroupRead;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models\DefaultTagRead;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories\EloquentTaggableRepository;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories\EloquentTagReadRepository;

class TagsServiceProvider extends ChiefPluginServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-tags', __DIR__ . '/Admin/resources/views');

        $this->loadMigrationsFrom(__DIR__.'/Infrastructure/migrations');

        $this->loadPluginAdminRoutes(__DIR__ . '/Admin/routes/chief-admin-routes.php');
    }

    public function register()
    {
        $this->app->bind(TagRead::class, fn () => DefaultTagRead::class);
        $this->app->bind(TagGroupRead::class, fn () => DefaultTagGroupRead::class);

        $this->app->bind(TagReadRepository::class, function ($app) {
            return $app->make(EloquentTagReadRepository::class);
        });

        $this->app->bind(TaggableRepository::class, function ($app) {
            return $app->make(EloquentTaggableRepository::class);
        });
    }
}
