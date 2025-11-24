<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;

class CreatePageFirstUrls
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
                $this->application->create(new CreateUrl($model->modelReference(), $site, $slug, 'offline'));
            }
        } catch (\Exception $e) {
            Log::error('Error creating initial URLs for model '.get_class($model).' with ID '.$model->getKey().': '.$e->getMessage());
        }
    }

    private function generateSlugsForAllSites(Visitable $model): array
    {
        $currentLocale = app()->getLocale();
        $slugs = [];

        if (! ($resource = $this->registry->findResourceByModel($model::class)) || ! $resource instanceof PageResource) {
            return [];
        }

        $locales = $model instanceof HasAllowedSites ? ChiefSites::verifiedLocales($model->getAllowedSites()) : ChiefSites::locales();

        foreach ($locales as $locale) {
            app()->setLocale($locale);
            $slugs[$locale] = Str::slug($resource->getPageTitle($model));
        }

        // Reset locale
        app()->setLocale($currentLocale);

        return array_filter($slugs, fn ($item) => $item);
    }
}
