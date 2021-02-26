<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\ManagedModels\Application\SortModels;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FragmentsAssistant
{
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesFragmentsAssistant(): array
    {
        return [
            ManagedRoute::post('fragments-reorder', 'fragments/{fragmentowner_id}/reorder'),
        ];
    }

    public function routeFragmentsAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if (! in_array($action, ['fragments-reorder'])) {
            return null;
        }

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function canFragmentsAssistant(string $action, $model = null): bool
    {
        return in_array($action, ['fragments-index', 'fragments-reorder']);
    }

    public function fragmentsReorder(Request $request, $ownerId)
    {
        if (! $request->indices) {
            throw new \InvalidArgumentException('Missing arguments [indices] for sorting request.');
        }

        $owner = $this->managedModelClass()::withoutGlobalScopes()->findOrFail($ownerId);

        app(SortModels::class)->handleFragments($owner, $request->indices);

        return response()->json([
            'message' => 'models sorted.',
        ]);
    }
}
