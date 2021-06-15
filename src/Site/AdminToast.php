<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;

class AdminToast
{
    private Request $request;
    private Registry $registry;

    public function __construct(Request $request, Registry $registry)
    {
        $this->request = $request;
        $this->registry = $registry;
    }

    public function getEditUrlOfCurrentPage(): ?string
    {
        $path = $this->request->path();
        $locale = app()->getLocale();

        // Remove the locale segment if present - we assume the first segment is the locale
        if (0 === strpos($path, $locale . '/') || $path === $locale) {
            $path = substr($path, strlen($locale . '/'));

            if (! $path) {
                $path = '/';
            }
        }

        $model = $this->findModelByUrl($path, $locale);
        $manager = $this->registry->manager($model::managedModelKey());

        if (! $manager->can('edit', $model)) {
            return null;
        }

        return $manager->route('edit', $model);
    }

    private function findModelByUrl(string $slug, string $locale)
    {
        $urlRecord = UrlRecord::findBySlug($slug, $locale);

        $modelReference = ModelReference::make($urlRecord->model_type, (string) $urlRecord->model_id);

        return $modelReference->instance();
    }
}
