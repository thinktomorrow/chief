<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\CommandPalette;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Managers\Register\Registry;

class CommandPaletteController extends Controller
{
    public function __construct()
    {
        $this->pageModels = $this->getAllPageModels();
        // dd($this->pageModels);
    }

    public function search(string $term = '')
    {
        $lowercaseTerm = Str::lower($term);

        $results = [];

        foreach ($this->pageModels as $modelGroup) {
            $resultGroup = [];

            foreach ($modelGroup['models'] as $model) {
                if (
                    Str::contains(Str::lower($model->adminConfig()->getModelName()), $lowercaseTerm) ||
                    Str::contains(Str::lower($model->adminConfig()->getIndexTitle()), $lowercaseTerm) ||
                    Str::contains(Str::lower($model->adminConfig()->getNavTitle()), $lowercaseTerm) ||
                    Str::contains(Str::lower($model->adminConfig()->getPageTitle()), $lowercaseTerm) ||
                    Str::contains(Str::lower($model->title ?? ''), $lowercaseTerm)
                ) {
                    $resultGroup[$model->modelReference()->getShort()] = [
                        'label' => $model->title,
                        'url' => '/admin/' . $model->managedModelKey() . '/' . $model->id . '/edit',
                    ];
                }
            }

            if (count($resultGroup) !== 0) {
                array_push($results, [
                    'label' => $modelGroup['label'],
                    'models' => $resultGroup,
                ]);
            }
        }

        return response()->view('chief::layout.nav.command-palette._result', [
            'term' => $term,
            'results' => $results,
        ]);
    }

    private function getAllPageModels(): Collection
    {
        return collect(app(Registry::class)->models())
            // Filter out fragment models
            ->filter(function ($model) {
                return ! in_array('Thinktomorrow\Chief\Fragments\Fragmentable', class_implements($model));
                // Return all instances of the models
            })->map(function ($model) {
                $models = $model::all();

                return [
                    'label' => $model::make()->adminConfig()->getModelName(),
                    'models' => $models,
                ];
            });
    }

    public static function getAllPageModelIndices(): Collection
    {
        return collect(app(Registry::class)->models())
            // Filter out fragment models
            ->filter(function ($model) {
                return ! in_array('Thinktomorrow\Chief\Fragments\Fragmentable', class_implements($model));
                // Return all instances of the models
            })->map(function ($model) {
                $model = $model::make();

                return [
                    'label' => $model->adminConfig()->getNavTitle(),
                    'url' => '/admin/' . $model->managedModelKey(),
                ];
            });
    }
}
