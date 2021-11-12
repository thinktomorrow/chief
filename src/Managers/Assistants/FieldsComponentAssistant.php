<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FieldsComponentAssistant
{
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
     * @param mixed $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function fieldsEdit(Request $request, $id, string $tag)
    {
        $model = $this->fieldsModel($id);

        $this->guard('fields-edit', $model);

        View::share('manager', $this);
        View::share('model', $model);
        View::share('tag', $tag);
        View::share('fields', Fields::make($model->fields())->tagged($tag)->model($model));

        /**
         * Custom view for tagged fieldgroups. e.g. tag sidebar can have
         * a custom sidebar view via method sidebarFieldsAdminView.
         */
        $method = Str::camel($tag).'FieldsAdminView';
        if (public_method_exists($model, $method)) {
            return $model->{$method}();
        }

        return view('chief::manager.fields.edit');
    }

    public function fieldsUpdate(Request $request, $id, string $tag)
    {
        $model = $this->fieldsModel($id);

        $this->guard('fields-update', $model);

        $fields = Fields::make($model->fields())
            ->model($model)
            ->tagged($tag)
        ;

        $this->fieldValidator()->handle($fields, $request->all());

        $model->saveFields($fields, $request->all(), $request->allFiles());

        return response()->json([
            'message' => 'fields updated',
            'data' => [],
        ], 200);
    }

    abstract protected function fieldsModel($id);

    abstract protected function guard(string $action, $model = null);
}
