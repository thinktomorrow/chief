<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class CreateUrlForPage
{
    private SaveUrlSlugs $saveUrlSlugs;
    private Registry $registry;

    public function __construct(SaveUrlSlugs $saveUrlSlugs, Registry $registry)
    {
        $this->saveUrlSlugs = $saveUrlSlugs;
        $this->registry = $registry;
    }

    public function onManagedModelCreated(ManagedModelCreated $event): void
    {
        $model = $event->modelReference->instance();

        if (! $model instanceof Visitable) {
            return;
        }

        $slugs = $this->createLocalizedSlugArray($model);

        if (count($slugs) < 0) {
            return;
        }

        try {
            $this->saveUrlSlugs->handle($model, $slugs);
        } catch (\Exception $e) {
            report($e);
        }
    }

    /**
     * @param Visitable $model
     * @return array
     */
    private function createLocalizedSlugArray(Visitable $model): array
    {
        $currentLocale = app()->getLocale();
        $slugs = [];

        if (! ($resource = $this->registry->findResourceByModel($model::class)) || ! $resource instanceof PageResource) {
            return [];
        }

        foreach (config('chief.locales') as $locale) {
            app()->setLocale($locale);
            $slugs[$locale] = Str::slug($resource->getPageTitle($model));
        }

        app()->setLocale($currentLocale);

        return array_filter($slugs, fn ($item) => $item);
    }
}
