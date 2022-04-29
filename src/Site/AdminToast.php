<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound;

class AdminToast
{
    private Request $request;
    private Registry $registry;

    public function __construct(Request $request, Registry $registry)
    {
        $this->request = $request;
        $this->registry = $registry;
    }

    public function discoverEditUrl(string $path, string $locale): ?string
    {
        // Remove the locale segment if present - we assume the first segment is the locale
        if (0 === strpos($path, $locale.'/') || $path === $locale) {
            $path = substr($path, strlen($locale.'/'));

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
