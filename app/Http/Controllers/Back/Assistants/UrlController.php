<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Concerns\Morphable\Morphables;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Urls\UrlRecord;

class UrlController extends Controller
{
    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public function checkSlugExists(Request $request, $key, $id)
    {
        $manager = $this->managers->findByKey($key, $id);

        $exists = UrlRecord::existsIgnoringRedirects($request->slug, null, $manager->model());

        return response()->json([
            'exists' => $exists,
            'hint' => $this->hint($request->slug, $exists),
        ]);
    }

    /**
     * @param string $slug
     * @param bool $exists
     * @return string
     */
    private function hint(string $slug, bool $exists): string
    {
        if (!$exists) {
            return '';
        }

        $urlRecord = UrlRecord::where('slug', $slug)->first();

        return 'Deze link bestaat reeds. Kies een andere of <a target="_blank" href="' . $this->editUrlOfExistingModel($urlRecord) . '">pas de andere pagina aan</a>.';
    }

    private function editUrlOfExistingModel(UrlRecord $urlRecord): string
    {
        $model = Morphables::instance($urlRecord->model_type)->find($urlRecord->model_id);

        return app(Managers::class)->findByModel($model)->route('edit') . '#inhoud';
    }
}
