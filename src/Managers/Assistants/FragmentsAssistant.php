<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\ManagedModels\Application\SortModels;

trait FragmentsAssistant
{
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesFragmentsAssistant(): array
    {
        return [
            ManagedRoute::post('fragments-reorder', 'fragments/reorder'),
        ];
    }

    public function routeFragmentsAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if(!in_array($action, ['fragments-reorder'])) return null;

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function canFragmentsAssistant(string $action, $model = null): bool
    {
        return in_array($action, ['fragments-index', 'fragments-reorder']);
    }

    public function fragmentsReorder(Request $request)
    {
        if(!$request->indices) {
            throw new \InvalidArgumentException('Missing arguments [indices] for sorting request.');
        }

        app(SortModels::class)->handle(FragmentModel::class, $request->indices, 'order', false);

        return response()->json([
            'message' => 'models sorted.'
        ]);
    }
}
