<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\ManagedModels\Events\PageChanged;
use Thinktomorrow\Chief\ManagedModels\Filters\Filters;
use Thinktomorrow\Chief\ManagedModels\Filters\Presets\HiddenFilter;
use Thinktomorrow\Chief\Managers\Exceptions\NotAllowedManagerAction;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait StateAssistant
{
    abstract protected function fieldsModel($id);

    public function routesStateAssistant(): array
    {
        return [
            ManagedRoute::get('state-window', '{id}/state/{key}/window'),
            ManagedRoute::get('state-edit', '{id}/state/{key}'),
            ManagedRoute::put('state-update', '{id}/state/{key}/{transitionKey}'),
        ];
    }

    public function canStateAssistant(string $action, $model = null): bool
    {
        if (! in_array($action, ['state-edit','state-update'])) {
            return false;
        }

        try {
            $this->authorize('update-page');
        } catch (NotAllowedManagerAction $e) {
            return false;
        }

        if (! $model || ! $model instanceof StatefulContract) {
            return false;
        }

        return true;
    }

    /**
     * This is the response when the state window is being refreshed.
     */
    public function stateWindow(Request $request, $id, $key)
    {
        $model = $this->fieldsModel($id);
        $stateConfig = $model->getStateConfig($key);

        return view('chief::manager.windows.state.window', [
            'manager' => app(Registry::class)->findManagerByModel($model::class),
            'model' => $model,
            'stateConfig' => $stateConfig,
            'allowedToEdit' => count(StateMachine::fromConfig($model, $stateConfig)->getAllowedTransitions()) > 0,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function stateEdit(Request $request, $id, $key)
    {
        $model = $this->fieldsModel($id);

        $this->guard('state-edit', $model);

        $stateConfig = $model->getStateConfig($key);
        $stateMachine = StateMachine::fromConfig($model, $stateConfig);

        return view('chief::manager.windows.state.edit', [
            'manager' => $this->registry->findManagerByModel($model::class),
            'model' => $model,
            'stateConfig' => $stateConfig,
            'allowedTransitionKeys' => $stateMachine->getAllowedTransitions(),
        ]);
    }

    public function stateUpdate(Request $request, $id, $key, $transitionKey)
    {
        /** @var StatefulContract $model */
        $model = $this->fieldsModel($id);

        $this->guard('state-edit', $model);

        $stateConfig = $model->getStateConfig($key);

        if ($stateConfig instanceof StateAdminConfig) {
            $this->saveTransitionFields($model, $stateConfig->getTransitionFields($transitionKey, $model), $request);
        }

        $machine = StateMachine::fromConfig($model, $stateConfig);

        try {
            $machine->apply($transitionKey);
            $model->save();

            $stateConfig->emitEvent($model, $transitionKey, $request->all());

            event(new PageChanged($model->modelReference()));
        } catch (StateException $e) {
            return response()->json([
                'message' => 'Transition ['.$transitionKey.'] not applied',
            ], 304);
        }

        // In case that the state makes the model inaccessible (such as a deletion)
        // we'll want to redirect to a different page.
        $redirect = $stateConfig->getRedirectAfterTransition($transitionKey, $model);

        // A custom redirect is present so we'll return to the redirect.
        if ($redirect && ! $request->expectsJson()) {
            if ($notification = $stateConfig->getResponseNotification($transitionKey)) {
                return redirect()->to($redirect)->with(
                    'messages.' . ($stateConfig->getTransitionType($transitionKey) ?: 'info'),
                    $notification
                );
            }

            return redirect()->to($redirect);
        }

        // Default when we don't have a custom redirect and no json response
        // is expected, we'll go back to the current page
        if (! $request->expectsJson()) {
            return redirect()->back();
        }

        return response()->json([
            'message' => 'Transition ['.$transitionKey.'] applied',
            'redirect_to' => $redirect,
        ]);
    }

    private function saveTransitionFields($model, iterable $fields, Request $request)
    {
        $fields = Fields::make($fields);

        if ($fields->isEmpty()) {
            return;
        }

        $this->fieldValidator()->handle($fields, $request->all());

        app($this->resource->getSaveFieldsClass())->save($model, $fields, $request->all(), $request->allFiles());
    }

    public function filtersStateAssistant(): Filters
    {
        $modelClass = $this->managedModelClass();

        // order results by publication date.
        if (Schema::hasColumn((new $modelClass)->getTable(), 'published_at')) {
            return new Filters([
                HiddenFilter::make('publish', function ($query) {
                    return $query->orderBy('published_at', 'DESC');
                }),
            ]);
        }

        return new Filters();
    }
}
