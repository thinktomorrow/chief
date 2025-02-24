<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\AdminToast;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound;

class GuessEditUrl
{
    protected Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function guessByPathAndLocale(string $path, string $locale, ?string $localeSegment = null): ?string
    {
        // Remove the locale segment if present - we assume the first segment is the locale
        $localeSegment = $localeSegment ?: $locale;

        if (str_starts_with($path, $localeSegment.'/') || $path === $localeSegment) {
            $path = substr($path, strlen($localeSegment.'/'));

            if (! $path) {
                $path = '/';
            }
        }

        try {
            $model = $this->findModelByUrl($path, $locale);
        } catch (UrlRecordNotFound $e) {
            return null;
        }

        $manager = $this->registry->findManagerByModel($model::class);

        if (! $manager->can('edit', $model)) {
            return null;
        }

        return $manager->route('edit', $model);
    }

    /**
     * @throws Urls\UrlRecordNotFound
     * @throws \Thinktomorrow\Chief\Shared\ModelReferences\CannotInstantiateModelReference
     */
    private function findModelByUrl(string $slug, string $locale)
    {
        $urlRecord = UrlRecord::findBySlug($slug, $locale);

        $modelReference = ModelReference::make($urlRecord->model_type, (string) $urlRecord->model_id);

        return $modelReference->instance();
    }
}
