<?php

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait CreateAssistant
{
    abstract protected function fieldsModel($id);

    abstract protected function fieldValidator(): FieldValidator;

    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    abstract protected function guard(string $action, $model = null);

    public function routesCreateAssistant(): array
    {
        return [
            ManagedRoute::get('create'),
            ManagedRoute::post('store'),
        ];
    }

    public function canCreateAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['create', 'store'])) {
            return false;
        }

        try {
            $this->authorize('create-page');
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        return true;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create(Request $request)
    {
        $model = $this->managedModelClassInstance(...$this->resource->getInstanceAttributes($request));

        View::share('manager', $this);
        View::share('model', $model);
        View::share('resource', $this->resource);

        View::share('forms', Forms::make($this->resource->fields($model))
            ->fillModel($model)
            ->fillFields($this, $model));

        return $this->resource->getCreatePageView();
    }

    public function store(Request $request)
    {
        $this->guard('store');

        $model = $this->handleStore($request);

        $redirectAfterCreate = $this->resource->getRedirectAfterCreate($model);

        if ($request->expectsJson()) {
            return response()->json([
                'redirect_to' => $redirectAfterCreate,
            ]);
        }

        return redirect()->to($redirectAfterCreate);
    }

    private function handleStore(Request $request)
    {
        $model = $this->managedModelClassInstance(...$this->resource->getInstanceAttributes($request));

        $fields = Forms::make($this->resource->fields($model))
            ->fillModel($model)
            ->getFields()
            ->notTagged(['edit', 'not-on-create']);

        $this->fieldValidator()->handle($fields, $request->all());

        // TODO: extract all uploadedFile instances from the input...
        app($this->resource->getSaveFieldsClass())->save($model, $fields, $request->all(), $request->allFiles());

        event(new ManagedModelCreated($model->modelReference()));

        return $model;
    }
}
