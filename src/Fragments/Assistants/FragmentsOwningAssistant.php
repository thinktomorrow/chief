<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\View\Components\Fragments;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\Actions\SortModels;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FragmentsOwningAssistant
{
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesFragmentsOwningAssistant(): array
    {
        return [
            ManagedRoute::get('fragments-show', 'fragments/{fragmentowner_id}/window'),
            ManagedRoute::get('fragments-select-new', 'fragments/{fragmentowner_id}/new-fragment'),
            ManagedRoute::get('fragments-select-existing', 'fragments/{fragmentowner_id}/existing'),

            ManagedRoute::post('fragments-reorder', 'fragments/{fragmentowner_id}/reorder'),

            // Nested fragments - not used in views but only for internal application logic
            ManagedRoute::get('nested-fragments-select-new', 'nestedfragments/{fragmentowner_id}/new'),
            ManagedRoute::get('nested-fragments-select-existing', 'nestedfragments/{fragmentowner_id}/existing'),
            ManagedRoute::post('nested-fragments-reorder', 'nestedfragments/{fragmentmodelowner_id}/reorder'),
        ];
    }

    public function routeFragmentsOwningAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if (! in_array($action, [
            'fragments-show',
            'fragments-select-new',
            'fragments-select-existing',
            'fragments-reorder',
        ])) {
            return null;
        }

        // TODO: would be great if we could remove this specific 'nested' attitude here and just use the same routes for everything
        if ($model instanceof Fragmentable) {
            $model = $model->fragmentModel();
//            return route('chief.' . $this->managedModelClass()::resourceKey() . '.nested-' . $action, array_merge([$model->fragmentModel()->id], $parameters));
        }

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function canFragmentsOwningAssistant(string $action, $model = null): bool
    {
        if ($model && ! $model instanceof FragmentsOwner) {
            return false;
        }

        return in_array($action, [
            'fragments-index',
            'fragments-show', // window
            'fragments-select-new',
            'fragments-select-existing',
            'fragments-reorder',
        ]);
    }

    public function fragmentsSelectNew(Request $request, $ownerId)
    {
        $owner = $this->owner($ownerId);

        return view('chief::manager.windows.fragments.component.fragment-select-new', [
            'fragments' => $this->getAllowedFragments($owner),
            'owner' => $owner,
            'order' => $request->input('order', 0),
        ]);
    }

    public function fragmentsSelectExisting(Request $request, $ownerId)
    {
        $owner = $this->owner($ownerId);

        return $this->showFragmentsSelectExisting($owner, $this->getSharedFragments($owner, $request), $request->input('order', 0));
    }

    private function showFragmentsSelectExisting($owner, $sharedFragments, $order)
    {
        // Select filter by owner
        $existingOwnersOptions = [];
        foreach ($owner->getRelatedOwners() as $relatedOwner) {
            $existingOwnersOptions[$relatedOwner->modelReference()->get()] = $this->registry->findResourceByModel($relatedOwner::class)->getPageTitle($relatedOwner);
        }

        // Select filter by fragment class
        $existingTypesOptions = [];
        foreach ($owner->allowedFragments() as $allowedFragmentClass) {
            $existingTypesOptions[$allowedFragmentClass] = $this->registry->findResourceByModel($allowedFragmentClass)->getLabel();
        }

        return view('chief::manager.windows.fragments.component.fragment-select-existing', [
            'sharedFragments' => $sharedFragments,
            'owner' => $owner,
            'ownerManager' => $this,
            'order' => $order,
            'existingTypesOptions' => $existingTypesOptions,
            'existingOwnersOptions' => $existingOwnersOptions,
        ]);
    }

    public function fragmentsReorder(Request $request, $ownerId)
    {
        $owner = $this->owner($ownerId);

        /**
         * Sortable.js contains dummy indices such as 5wj, cfv and such. Here we make sure
         * that these values are excluded. Since a fragment id consist of at least 4 digits,
         * We can safely assume that an index with less than four characters is considered an invalid fragment id.
         */
        $indices = array_filter($request->input('indices', []), fn ($index) => strlen((string) $index) > 3);

        app(SortModels::class)->handleFragments($owner->ownerModel(), $indices);

        return response()->json([
            'message' => 'models sorted.',
        ]);
    }

    private function getAllowedFragments(FragmentsOwner $owner): array
    {
        return array_map(function ($fragmentableClass) {
            $resource = $this->registry->findResourceByModel($fragmentableClass);
            $modelClass = $resource::modelClassName();

            return [
                'manager' => $this->registry->findManagerByModel($fragmentableClass),
                'model' => app($modelClass),
                'resource' => $resource,
            ];
        }, $owner->allowedFragments());
    }

    private function getSharedFragments(FragmentsOwner $owner, Request $request): array
    {
        return $this->fragmentRepository->getAllShared($owner, [
            'exclude_own' => true,
            'default_top_shared' => true,
            'owners' => array_filter($request->input('owners', []), fn ($val) => $val),
            'types' => array_filter($request->input('types', []), fn ($val) => $val),
        ])->map(function ($fragmentable) {
            return [
                'manager' => $this->registry->findManagerByModel($fragmentable::class),
                'model' => $fragmentable,
                'resource' => $this->registry->findResourceByModel($fragmentable::class),
            ];
        })->all();
    }

    public function fragmentsShow(Request $request, $ownerId): string
    {
        $owner = $this->owner($ownerId);

        return (new Fragments($owner))->render()->render();
    }

    private function owner($ownerId): FragmentsOwner
    {
        if ((new \ReflectionClass($this->managedModelClass()))->implementsInterface(Fragmentable::class)) {
            return $this->fragmentRepository->find($ownerId);
        }

        return $this->managedModelClass()::withoutGlobalScopes()->findOrFail($ownerId);
    }


//    public function nestedFragmentsSelectNew(Request $request, $fragmentModelId)
//    {
//        $owner = $this->fragmentRepository->find($fragmentModelId);
//
//        return $this->showFragmentsSelectNew($owner, $this->getAllowedFragments($owner), $request->input('order', 0));
//    }

//    private function showFragmentsSelectNew($owner, $fragments, $order)
//    {
//        return view('chief::manager.windows.fragments.component.fragment-select-new', [
//            'fragments' => $fragments,
//            'owner' => $owner,
//            'order' => $order,
//        ]);
//    }

//    public function nestedFragmentsSelectExisting(Request $request, $fragmentModelId)
//    {
//        $owner = $this->fragmentRepository->find($fragmentModelId);
//
//        return $this->showFragmentsSelectExisting($owner, $this->getSharedFragments($owner, $request), $request->input('order', 0));
//    }

//    public function nestedFragmentsReorder(Request $request, $fragmentModelId)
//    {
//        $owner = $this->fragmentRepository->find($fragmentModelId);
//
//        return $this->handleFragmentsReorder($owner->fragmentModel(), $request->input('indices'));
//    }

//    private function handleFragmentsReorder(Model $ownerModel, array $indices)
//    {
//        /**
//         * Sortable.js contains dummy indices such as 5wj, cfv and such. Here we make sure
//         * that these values are excluded. Since a fragment id consist of at least 4 digits,
//         * We can safely assume that an index with less than four characters is considered an invalid fragment id.
//         */
//        $indices = array_filter($indices, fn ($index) => strlen((string) $index) > 3);
//
//        app(SortModels::class)->handleFragments($ownerModel, $indices);
//
//        return response()->json([
//            'message' => 'models sorted.',
//        ]);
//    }
}
