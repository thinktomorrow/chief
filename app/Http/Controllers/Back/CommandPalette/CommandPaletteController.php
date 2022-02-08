<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\CommandPalette;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class CommandPaletteController extends Controller
{
    public function __construct()
    {
        $this->pageModels = $this->getAllPageModels();
    }

    public function search(string $term = '')
    {
        $results = [];

        foreach ($this->pageModels as $model) {
            if($term === $model->adminConfig()->getModelName()) {
                array_push($results, [
                    'title' => $model->title,
                    'url' => '/admin/' . $model->managedModelKey() . '/' . $model->id . '/edit',
                ]);
            }
        }

        return response()->view('chief::layout.nav.command-palette._result', [
            'term' => $term,
            'results' => $results
        ]);
    }

    private function getAllPageModels(): Collection
    {
        return collect(app(Registry::class)->models())
            // Filter out fragment models
            ->filter(function($model) {
                return !in_array('Thinktomorrow\Chief\Fragments\Fragmentable', class_implements($model));
            // Return all instances of the models
            })->map(function($model) {
                return $model::all();
            // Flatten all models to the same level
            })->flatten(1);
    }
}
