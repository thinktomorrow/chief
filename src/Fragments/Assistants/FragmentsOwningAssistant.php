<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\ManagedModels\Application\SortModels;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FragmentsOwningAssistant
{
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesFragmentsOwningAssistant(): array
    {
        return [
            ManagedRoute::post('fragments-reorder', 'fragments/{fragmentowner_id}/reorder'),
        ];
    }

    public function routeFragmentsOwningAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if (! in_array($action, ['fragments-reorder'])) {
            return null;
        }

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function canFragmentsOwningAssistant(string $action, $model = null): bool
    {
        return in_array($action, ['fragments-index', 'fragments-reorder']);
    }

    public function fragmentsReorder(Request $request, $ownerId)
    {
        if (! $request->indices) {
            throw new \InvalidArgumentException('Missing arguments [indices] for sorting request.');
        }

        $owner = $this->managedModelClass()::withoutGlobalScopes()->findOrFail($ownerId);

        app(SortModels::class)->handleFragments($ownerId, $request->indices);

        return response()->json([
            'message' => 'models sorted.',
        ]);
    }
}
