<?php

namespace Thinktomorrow\Chief\Plugins\Tags;

use Illuminate\Support\ServiceProvider;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagGroupRead;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagRead;
use Thinktomorrow\Chief\Plugins\Tags\Application\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\Application\Taggable\TaggableRepository;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models\DefaultTagGroupRead;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Models\DefaultTagRead;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories\EloquentTaggableRepository;
use Thinktomorrow\Chief\Plugins\Tags\Infrastructure\Repositories\EloquentTagReadRepository;

class TagsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['view']->addNamespace('chief-tags', __DIR__ . '/Admin/resources/views');
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
