<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Forms\Events\FormUpdated;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait FormsAssistant
{
    public function routesFormsAssistant(): array
    {
        return [
            ManagedRoute::get('form-edit', '{id}/forms/{formId}/edit'),
            ManagedRoute::put('form-update', '{id}/forms/{formId}/update'),
            ManagedRoute::get('form-show', '{id}/forms/{formId}/show'),
        ];
    }

    public function canFormsAssistant(string $action, $model = null): bool
    {
        return in_array($action, ['form-edit', 'form-update', 'form-show']);
    }

    /**
     * @param  mixed  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function formEdit(Request $request, $id, string $formId)
    {
        $model = $this->fieldsModel($id);

        $this->guard('form-edit', $model);

        // TODO: unify default form routes to the form itself...
        View::share('form', Layout::make($this->resource->fields($model))->findForm($formId)->fill($this, $model));

        return view('chief-form::templates.form-in-sidebar');
    }

    public function formUpdate(Request $request, $id, string $formId)
    {
        $model = $this->fieldsModel($id);

        $this->guard('form-update', $model);

        $form = Layout::make($this->resource->fields($model))
            ->fillModel($model)
            ->find($formId);

        $fields = $form->getFields();

        $this->fieldValidator()->handle($fields, $request->all());

        app($this->resource->getSaveFieldsClass())->save($model, $fields, $request->all(), $request->allFiles());

        event(new FormUpdated($model->modelReference(), $formId));

        return response()->json([
            'message' => 'fields updated',
            'data' => [],
            'redirect_to' => $form->getRedirectAfterSubmit() ?: null,
        ], 200);
    }

    public function formShow(Request $request, $id, string $formId)
    {
        $model = $this->fieldsModel($id);

        $this->guard('form-show', $model);

        return Layout::make($this->resource->fields($model))->find($formId)->fill($this, $model)->toHtml();
    }

    abstract protected function fieldsModel($id);

    abstract protected function guard(string $action, $model = null);
}
