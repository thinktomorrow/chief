<?php

namespace Thinktomorrow\Chief\Managers\Assistants;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Forms\Forms;
use Thinktomorrow\Chief\Fragments\Repositories\ContextRepository;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUpdated;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StateAdminConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait EditAssistant
{
    abstract protected function fieldsModel($id);

    abstract protected function fieldValidator(): FieldValidator;

    abstract protected function generateRoute(string $action, $model = null, ...$parameters): string;

    abstract protected function guard(string $action, $model = null);

    public function routesEditAssistant(): array
    {
        return [
            ManagedRoute::get('edit', '{id}/edit'),
            ManagedRoute::put('update', '{id}/update'),
        ];
    }

    public function canEditAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['edit', 'update'])) {
            return false;
        }

        try {
            $this->authorize('update-page');
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        // Model cannot be in deleted state for editing purposes.
        if ($model && $model instanceof StatefulContract) {
            return ! ($model->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY) == PageState::deleted);
        }

        return true;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        $this->guard('edit', $model);

        // TODO/ remove share??
        View::share('manager', $this);
        View::share('model', $model);
        View::share('resource', $this->resource);
        View::share('forms', Forms::make($this->resource->fields($model))->fill($this, $model));

        // Find or create the context... TODO: this should be something to do elsewhere?

        // WIP
        $contexts = app(ContextRepository::class)->getByOwner($model);
        View::share('contexts', $contexts);
        View::share('context', $contexts->first());
        View::share('contextsForSwitch', $contexts);

        //        $contextsForSwitch = app(\Thinktomorrow\Chief\Fragments\Models\ContextRepository::class)->getOrCreateByOwner($model)->map(function($context){ return [
        //            'id' => $context->id,
        //            'locale' => $context->locale,
        //            'refreshUrl' => route('chief::fragments.refresh-index', $context->id)
        //        ];
        //        });

        $stateConfigs = [];

        if ($model instanceof StatefulContract) {
            $stateConfigs = collect($model->getStateKeys())
                ->map(fn (string $stateKey) => $model->getStateConfig($stateKey))
                ->filter(fn ($stateConfig) => $stateConfig instanceof StateAdminConfig)
                ->all();
        }

        View::share('stateConfigs', $stateConfigs);

        return $this->resource->getPageView();
    }

    public function update(Request $request, $id)
    {
        $model = $this->handleUpdate($request, $id);

        return redirect()->to($this->route('index'))
            ->with('messages.success', '<i class="fa fa-fw fa-check-circle"></i>  <a href="'.$this->route('edit', $model).'">'.$this->resource->getPageTitle($model).'</a> is aangepast');
    }

    private function handleUpdate(Request $request, $id)
    {
        $model = $this->fieldsModel($id);

        $this->guard('update', $model);

        $fields = Forms::make($this->resource->fields($model))
            ->fillModel($model)
            ->getFields();

        $this->fieldValidator()->handle($fields, $request->all());

        app($this->resource->getSaveFieldsClass())->save($model, $fields, $request->all(), $request->allFiles());

        event(new ManagedModelUpdated($model->modelReference()));

        return $model;
    }
}
