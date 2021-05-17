<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;

class CreateUrlForPage
{
    private SaveUrlSlugs $saveUrlSlugs;

    public function __construct(SaveUrlSlugs $saveUrlSlugs)
    {
        $this->saveUrlSlugs = $saveUrlSlugs;
    }

    public function onManagedModelCreated(ManagedModelCreated $event): void
    {
        $model = $event->modelReference->instance();

        if(!$model instanceof Visitable) return;

        $slugs = $this->createLocalizedSlugArray($model);

        if(count($slugs) < 0) return;

        $this->saveUrlSlugs->handle($model, $slugs);
    }

    /**
     * @param Visitable $model
     * @return array
     */
    private function createLocalizedSlugArray(Visitable $model): array
    {
        $currentLocale = app()->getLocale();
        $slugs = [];

        foreach (config('chief.locales') as $locale) {
            app()->setLocale($locale);
            $slugs[$locale] = Str::slug($model->title);
        }

        app()->setLocale($currentLocale);

        return array_filter($slugs, fn($item) => $item);
    }
}
