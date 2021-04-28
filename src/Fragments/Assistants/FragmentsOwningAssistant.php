<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\ManagedModels\Actions\SortModels;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FragmentsOwningAssistant
{
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesFragmentsOwningAssistant(): array
    {
        return [
            ManagedRoute::get('fragments-select-new', 'fragments/{fragmentowner_id}/new'),
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
            'fragments-select-new',
            'fragments-select-existing',
            'fragments-reorder',
        ])) {
            return null;
        }

        if ($model instanceof Fragmentable && $model->isFragment()) {
            return route('chief.' . $this->managedModelClass()::managedModelKey() . '.nested-' . $action, array_merge([$model->fragmentModel()->id], $parameters));
        }

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function canFragmentsOwningAssistant(string $action, $model = null): bool
    {
        return in_array($action, [
            'fragments-index',
            'fragments-select-new',
            'fragments-select-existing',
            'fragments-reorder',
        ]);
    }

    public function fragmentsSelectNew(Request $request, $ownerId)
    {
        $owner = $this->managedModelClass()::withoutGlobalScopes()->findOrFail($ownerId);

        return $this->showFragmentsSelectNew($owner, $this->getAllowedFragments($owner));
    }

    public function nestedFragmentsSelectNew(Request $request, $fragmentModelId)
    {
        $owner = $this->fragmentRepository->find($fragmentModelId);

        return $this->showFragmentsSelectNew($owner, $this->getAllowedFragments($owner->fragmentModel()));
    }

    private function showFragmentsSelectNew($owner, $fragments)
    {
        return view('chief::manager.cards.fragments.component.fragment-select-new', [
            'fragments' => $fragments,
            'owner' => $owner,
        ]);
    }

    public function fragmentsReorder(Request $request, $ownerId)
    {
        $owner = $this->managedModelClass()::withoutGlobalScopes()->findOrFail($ownerId);

        return $this->handleFragmentsReorder($owner, $request->input('indices'));
    }

    public function nestedFragmentsReorder(Request $request, $fragmentModelId)
    {
        $owner = $this->fragmentRepository->find($fragmentModelId);

        return $this->handleFragmentsReorder($owner->fragmentModel(), $request->input('indices'));
    }

    private function handleFragmentsReorder(Model $ownerModel, array $indices)
    {
        app(SortModels::class)->handleFragments($ownerModel, $indices);

        return response()->json([
            'message' => 'models sorted.',
        ]);
    }

    private function getAllowedFragments(Model $owner): array
    {
        return array_map(function ($fragmentableClass) {
            $modelClass = $this->registry->modelClass($fragmentableClass::managedModelKey());

            return [
                'manager' => $this->registry->manager($fragmentableClass::managedModelKey()),
                'model' => new $modelClass(),
            ];
        }, $owner->allowedFragments());
    }

    private function getSharedFragments(Model $owner): array
    {
        $fragmentModelIds = $this->fragmentRepository->getByOwner($owner)->map(fn ($fragment) => $fragment->fragmentModel())->pluck('id')->toArray();

        return $this->fragmentRepository->shared()->reject(function ($fragmentable) use ($fragmentModelIds) {
            return in_array($fragmentable->fragmentModel()->id, $fragmentModelIds);
        })->map(function ($fragmentable) {
            return [
                'manager' => $this->registry->manager($fragmentable::managedModelKey()),
                'model' => $fragmentable,
            ];
        })->all();
    }
}
