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
                    Str::contains(Str::lower($model->modelReference()), $term) ||
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

                // Add model index page to result group
                array_push($resultGroup, [
                    'label' => ucfirst($modelGroup['label']) . ' overzicht',
                    'url' => '/admin/' . $firstModel->managedModelKey(),
                ]);

                // Add result group to search results
                array_push($results, [
                    'label' => $modelGroup['label'],
                    'results' => $resultGroup,
                ]);
            }
        }

        return $results;
    }

    public function searchThroughAdminPages($term)
    {
        // Can we generate this in a different way, or move these out of this method
        $adminPages = collect([
            [ 'label' => 'Dashboard', 'url' => route('chief.back.dashboard'), 'permission' => null, 'tags' => ['home'], ],
            [ 'label' => 'Menu', 'url' => route('chief.back.menus.index'), 'permission' => 'update-page', 'tags' => ['navigatie'], ],
            [ 'label' => 'Media', 'url' => route('chief.mediagallery.index'), 'permission' => 'update-page', 'tags' => ['mediagalerij', 'mediabibliotheek', 'assets'], ],
            [ 'label' => 'Teksten', 'url' => route('squanto.index'), 'permission' => 'view-squanto', 'tags' => ['squanto', 'mediagalerij', 'mediabibliotheek', 'assets'], ],
            [ 'label' => 'Sitemap', 'url' => route('chief.back.sitemap.show'), 'permission' => null, 'tags' => [], ],
            [ 'label' => 'Admins', 'url' => route('chief.back.users.index'), 'permission' => 'view-user', 'tags' => [], ],
            [ 'label' => 'Rechten', 'url' => route('chief.back.roles.index'), 'permission' => 'view-role', 'tags' => ['roles'], ],
            [ 'label' => 'Settings', 'url' => route('chief.back.settings.edit'), 'permission' => 'update-setting', 'tags' => ['instellingen'], ],
            [ 'label' => 'Audit', 'url' => route('chief.back.audit.index'), 'permission' => 'view-audit', 'tags' => [], ],
            [ 'label' => chiefAdmin()->firstname, 'url' => route('chief.back.you.edit'), 'permission' => 'update-you', 'tags' => ['account'], ],
            [ 'label' => 'Logout', 'url' => route('chief.back.logout'), 'permission' => null, 'tags' => [], ],
        ]);

        $results = $adminPages->filter(function ($adminPage) use ($term) {
            // TODO: check if current user has necessary permissions to view page
            // if(! chiefAdmin()->hasPermissionTo($adminPage['permission'])) {
            //     return false;
            // }

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

        if (count($results) === 0) {
            return [];
        }

        return [
            [
                'label' => 'Chief',
                'results' => $results,
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
}
