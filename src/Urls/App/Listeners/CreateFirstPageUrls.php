<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Listeners;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;

class CreateFirstPageUrls
{
    private Registry $registry;

    private UrlApplication $application;

    public function __construct(UrlApplication $application, Registry $registry)
    {
        $this->registry = $registry;
        $this->application = $application;
    }

    public function onManagedModelCreated(ManagedModelCreated $event): void
    {
        $model = $event->modelReference->instance();

        if (! $model instanceof Visitable) {
            return;
        }

        $slugs = $this->generateSlugsForAllSites($model);

        if (count($slugs) < 0) {
            return;
        }

        try {
            foreach ($slugs as $site => $slug) {
                $this->application->create(new CreateUrl($model->modelReference(), $site, $slug, 'offline', null));
            }
        } catch (\Exception $e) {
            report($e);
        }
    }

    private function generateSlugsForAllSites(Visitable $model): array
    {
        $currentLocale = app()->getLocale();
        $slugs = [];

        if (! ($resource = $this->registry->findResourceByModel($model::class)) || ! $resource instanceof PageResource) {
            return [];
        }

        foreach (ChiefSites::all() as $site) {
            $siteLocale = $site->locale;

            app()->setLocale($siteLocale);
            $slugs[$siteLocale] = Str::slug($resource->getPageTitle($model));
        }

        // Reset locale
        app()->setLocale($currentLocale);

        return array_filter($slugs, fn ($item) => $item);
    }
}
