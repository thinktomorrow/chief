<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back\CommandPalette;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Managers\Register\Registry;

class CommandPaletteController extends Controller
{
    public function __construct()
    {
        $this->pageModels = $this->getAllPageModels();
    }

    public function search(string $term = '')
    {
        $lowercaseTerm = Str::lower($term);

        // Is it possible to generate results based on visitable routes,
        // instead of getting models and keeping a static list of admin pages?
        $results = [
            ...$this->searchThroughPageModels($lowercaseTerm),
            ...$this->searchThroughAdminPages($lowercaseTerm),
        ];

        return response()->view('chief::layout.nav.command-palette._result', [
            'term' => $term,
            'results' => $results,
        ]);
    }

    public function searchThroughPageModels($term)
    {
        $results = [];

        foreach ($this->pageModels as $modelGroup) {
            $resultGroup = [];

            foreach ($modelGroup['models'] as $model) {
                if (
                    Str::contains(Str::lower($model->adminConfig()->getModelName()), $term) ||
                    Str::contains(Str::lower($model->adminConfig()->getIndexTitle()), $term) ||
                    Str::contains(Str::lower($model->adminConfig()->getNavTitle()), $term) ||
                    Str::contains(Str::lower($model->adminConfig()->getPageTitle()), $term) ||
                    Str::contains(Str::lower($model->title ?? ''), $term)
                ) {
                    $resultGroup[$model->modelReference()->getShort()] = [
                        'label' => $model->title,
                        'url' => '/admin/' . $model->managedModelKey() . '/' . $model->id . '/edit',
                    ];
                }
            }

            if (count($resultGroup) !== 0) {
                $firstModel = $modelGroup['models']->first();

                array_push($resultGroup, [
                    'label' => ucfirst($modelGroup['label']) . ' overzicht',
                    'url' => '/admin/' . $firstModel->managedModelKey(),
                ]);

                array_push($results, [
                    'label' => $modelGroup['label'],
                    'models' => $resultGroup,
                ]);
            }
        }

        return $results;
    }

    public function searchThroughAdminPages($term)
    {
        $adminPages = collect([
            [
                'label' => 'Dashboard',
                'url' => route('chief.back.dashboard'),
                'permission' => null,
                'tags' => ['home']
            ], [
                'label' => 'Menu',
                'url' => route('chief.back.menus.index'),
                'permission' => 'update-page',
                'tags' => ['navigatie'],
            ], [
                'label' => 'Media',
                'url' => route('chief.mediagallery.index'),
                'permission' => 'update-page',
                'tags' => ['mediagalerij', 'mediabibliotheek', 'assets'],
            ], [
                'label' => 'Teksten',
                'url' => route('squanto.index'),
                'permission' => 'view-squanto',
                'tags' => ['squanto', 'mediagalerij', 'mediabibliotheek', 'assets'],
            ], [
                'label' => 'Sitemap',
                'url' => route('chief.back.sitemap.show'),
                'permission' => null,
                'tags' => [],
            ], [
                'label' => 'Admins',
                'url' => route('chief.back.users.index'),
                'permission' => 'view-user',
                'tags' => [],
            ], [
                'label' => 'Rechten',
                'url' => route('chief.back.roles.index'),
                'permission' => 'view-role',
                'tags' => ['roles'],
            ], [
                'label' => 'Settings',
                'url' => route('chief.back.settings.edit'),
                'permission' => 'update-setting',
                'tags' => ['instellingen'],
            ], [
                'label' => 'Audit',
                'url' => route('chief.back.audit.index'),
                'permission' => 'view-audit',
                'tags' => [],
            ], [
                'label' => chiefAdmin()->firstname,
                'url' => route('chief.back.you.edit'),
                'permission' => 'update-you',
                'tags' => ['account'],
            ], [
                'label' => 'Logout',
                'url' => route('chief.back.logout'),
                'permission' => null,
                'tags' => [],
            ],
        ]);

        $models = $adminPages->filter(function ($adminPage) use ($term) {
            // Check if label contains search term
            if (Str::contains(Str::lower($adminPage['label']), $term)) {
                return true;
            }

            // Check if any of tags contain search term
            if (collect($adminPage['tags'])->contains(function ($tag) use ($term) {
                return Str::contains(Str::lower($tag), $term);
            })) {
                return true;
            };

            return false;
        })->map(function ($adminPage) {
            return [
                'label' => $adminPage['label'],
                'url' => $adminPage['url'],
            ];
        })->toArray();

        if (count($models) === 0) {
            return [];
        }

        return [
            [
                'label' => 'Chief',
                'models' => $models,
            ],
        ];
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

    // Add these pages to end of model groups search result
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
