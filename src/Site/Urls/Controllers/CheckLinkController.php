<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class CheckLinkController
{
    public function check(Request $request)
    {
        /** @var ProvidesUrl|Model $model */
        $model = (new ModelReference($request->modelClass, (string) $request->modelId))->instance();

        // Trim slashes if any
        $slug = ($request->slug !== '/') ? trim($request->slug, '/') : $request->slug;

        $exists = UrlRecord::exists($slug, null, $model);

        return response()->json([
            'exists' => $exists,
            'hint' => $this->hint($slug, $exists),
        ]);
    }

    private function hint(string $slug, bool $exists): string
    {
        if (!$exists) {
            return '';
        }

        $urlRecord = UrlRecord::where('slug', $slug)->first();

        if ($urlRecord->isRedirect()) {
            return 'Deze link bestaat reeds als redirect. Deze redirect zal bijgevolg worden verwijderd.';
        }
        return 'Deze link bestaat reeds. Kies een andere of <a target="_blank" href="' . $this->editRouteOfOtherModel($urlRecord) . '">pas de andere pagina aan</a>.';
    }

    private function editRouteOfOtherModel(UrlRecord $urlRecord): string
    {
        /** @var ManagedModel $model */
        $model = $urlRecord->model;

        return app(Registry::class)->manager($model::managedModelKey())->route('edit', $model);
    }
}
