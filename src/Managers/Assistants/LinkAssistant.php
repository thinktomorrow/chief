<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;
use Thinktomorrow\Chief\Site\Urls\Controllers\LinksController;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait LinkAssistant
{
    abstract protected function fieldsModel($id);

    public function routesLinkAssistant(): array
    {
        return [
            ManagedRoute::get('links-edit', '{id}/links'),
            ManagedRoute::put('links-update', '{id}/links'),
        ];
    }

    public function canLinkAssistant(string $action, $model = null): bool
    {
        return (in_array($action, ['links-edit', 'links-update'])
            && ($model && $model instanceof Visitable));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function linksEdit(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        return view('chief::manager.windows.links.edit', [
            'linkForm' => LinkForm::fromModel($model),
            'manager' => app(Registry::class)->manager($model->managedModelKey()),
            'model' => $model,
        ]);
    }

    public function linksUpdate(Request $request, $id)
    {
        return app(LinksController::class)->update($request->merge([
            'modelClass' => $this->managedModelClass(),
            'modelId' => $id,
        ]));
    }
}
