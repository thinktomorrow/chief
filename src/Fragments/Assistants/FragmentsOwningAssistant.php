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
            ManagedRoute::post('fragments-reorder', 'fragments/{fragmentowner_id}/reorder'),
            ManagedRoute::post('nested-fragments-reorder', 'nestedfragments/{fragmentmodelowner_id}/reorder'),
        ];
    }

    public function routeFragmentsOwningAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if (! in_array($action, ['fragments-reorder'])) {
            return null;
        }

        if ($model instanceof Fragmentable && $model->isFragment()) {
            return route('chief.' . $this->managedModelClass()::managedModelKey() . '.nested-' . $action,                array_merge([$model->fragmentModel()->id], $parameters));
        }

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function canFragmentsOwningAssistant(string $action, $model = null): bool
    {
        return in_array($action, ['fragments-index', 'fragments-reorder']);
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
}
