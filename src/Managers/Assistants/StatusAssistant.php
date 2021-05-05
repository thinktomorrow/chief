<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Site\Urls\Controllers\LinksController; // TODO: needs refactoring
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;

trait StatusAssistant
{
    abstract protected function fieldsModel($id);

    public function routesStatusAssistant(): array
    {
        return [
            ManagedRoute::get('status-edit', '{id}/status'),
            ManagedRoute::put('status-update', '{id}/status'),
        ];
    }

    public function canStatusAssistant(string $action, $model = null): bool
    {
        return (in_array($action, ['status-edit', 'status-update'])
            && ($model && $model instanceof ProvidesUrl));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function statusEdit(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        return view('chief::manager.cards.status.edit', [
            'isAnyLinkOnline' => LinkForm::fromModel($model)->isAnyLinkOnline(),
            'manager' => app(Registry::class)->manager($model->managedModelKey()),
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
}
