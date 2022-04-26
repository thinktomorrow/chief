<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Site\Urls\Controllers\LinksController;
use Thinktomorrow\Chief\ManagedModels\States\PageState\WithPageState;

// TODO: needs refactoring

trait StatusAssistant
{
    abstract protected function fieldsModel($id);

    public function routesStatusAssistant(): array
    {
        return [
            ManagedRoute::get('status-window', '{id}/status/window'),
            ManagedRoute::get('status-edit', '{id}/status'),
            ManagedRoute::put('status-update', '{id}/status'),
        ];
    }

    public function canStatusAssistant(string $action, $model = null): bool
    {
        return (in_array($action, ['status-edit', 'status-update'])
            && ($model && $model instanceof WithPageState));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function statusEdit(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        return view('chief::manager.windows.status.edit', [
            'isAnyLinkOnline' => ($model instanceof Visitable && LinkForm::fromModel($model)->isAnyLinkOnline()),
            'isVisitable' => $model instanceof Visitable,
            'manager' => app(Registry::class)->findManagerByModel($model::class),
            'model' => $model,
        ]);
    }

    public function statusUpdate(Request $request, $id)
    {
        return app(LinksController::class)->update($request->merge([
            'modelClass' => $this->managedModelClass(),
            'modelId' => $id,
        ]));
    }

    public function statusWindow(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        return view('chief::manager.windows.status.window', [
            'manager' => app(Registry::class)->findManagerByModel($model::class),
            'model' => $model,
        ]);
    }
}
