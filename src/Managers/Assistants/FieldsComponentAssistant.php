<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FieldsComponentAssistant
{
    abstract protected function fieldsModel($id);

    abstract protected function guard(string $action, $model = null);

    public function routesFieldsComponentAssistant(): array
    {
        return [
            ManagedRoute::get('fields-edit', '{id}/fields/{componentKey}/edit'),
            ManagedRoute::put('fields-update', '{id}/fields/{componentKey}/update'),
        ];
    }

    public function canFieldsComponentAssistant(string $action, $model = null): bool
    {
        return in_array($action, ['fields-edit', 'fields-update']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fieldsEdit(Request $request, $id, string $componentKey)
    {
        $model = $this->fieldsModel($id);

        $this->guard('fields-edit', $model);

        return view('chief::manager.cards.fields.edit', [
            'manager' => $this,
            'model' => $model,
            'fields' => $componentKey !== "default"
                ? Fields::make($model->fields())->model($model)->component($componentKey)
                : Fields::make($model->fields())->model($model)->notTagged('component'),
            'componentKey' => $componentKey,
            'componentTitle' => $componentKey == 'chief-page-title' ? '' :  ucfirst($componentKey),
        ]);
    }

    public function fieldsUpdate(Request $request, $id, string $componentKey)
    {
        $model = $this->fieldsModel($id);

        $this->guard('fields-update', $model);

        $fields = $componentKey !== "default"
            ? Fields::make($model->fields())->model($model)->component($componentKey)
            : Fields::make($model->fields())->model($model)->notTagged('component');

        $this->fieldValidator()->handle($fields, $request->all());

        $model->saveFields($fields, $request->all(), $request->allFiles());

        return response()->json([
            'message' => 'fields updated',
            'data' => [],
        ], 200);
    }
}
