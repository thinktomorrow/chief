<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
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
    public function fieldsEdit(Request $request, $id, string $tag)
    {
        $model = $this->fieldsModel($id);

        $this->guard('fields-edit', $model);

        View::share('manager', $this);
        View::share('model', $model);

        return view('chief::manager.windows.fields.edit', [
            'tag' => $tag,
            // TODO: fix title
            'componentTitle' => 'Bewerk de velden',
        ]);
    }

    public function fieldsUpdate(Request $request, $id, string $tag)
    {
        $model = $this->fieldsModel($id);

        $this->guard('fields-update', $model);

        $fields = Fields::make($model->fields())
            ->model($model)
            ->tagged($tag);

        $this->fieldValidator()->handle($fields, $request->all());

        $model->saveFields($fields, $request->all(), $request->allFiles());

        return response()->json([
            'message' => 'fields updated',
            'data' => [],
        ], 200);
    }
}
