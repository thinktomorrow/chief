<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

trait FragmentsAssistant
{
    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    public function routesFragmentsAssistant(): array
    {
        return [
            ManagedRoute::get('fragments-index', '{id}/fragments'),
            ManagedRoute::put('fragments-reorder', '{id}/fragments/reorder'),
        ];
    }

    public function routeFragmentsAssistant(string $action, $model = null, ...$parameters): ?string
    {
        if(!in_array($action, ['fragments-index','fragments-reorder'])) return null;

        return $this->generateRoute($action, $model, ...$parameters);
    }

    public function canFragmentsAssistant(string $action, $model = null): bool
    {
        return in_array($action, ['fragments-index','fragments-reorder']);
    }

    public function fragmentsIndex(Request $request, $id)
    {
        $modelClass = $this->managedModelClass();

        /** @var FragmentsOwner $model */
        $model = $modelClass::findOrFail($id);

        // Current fragments
        $fragments = app(FragmentRepository::class)->getByOwner($model)->map(function(Fragmentable $model){
            return [
                'model'    => $model,
                'manager'  => app(Registry::class)->manager($model::managedModelKey()),
            ];
        });

        // Available fragments
        $allowedFragments = array_map(function($fragmentableClass){
            $modelClass = app(Registry::class)->modelClass($fragmentableClass::managedModelKey());
            return [
                'manager' => app(Registry::class)->manager($fragmentableClass::managedModelKey()),
                'model' => new $modelClass(),
            ];
        }, $model->allowedFragments());

        return view('chief::managers.fragments.index', [
            'model'     => $model,
            'manager'   => $this,
            'fragments' => $fragments,
            'allowedFragments' => $allowedFragments,
        ]);
    }
}
