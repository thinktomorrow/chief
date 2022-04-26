<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoute;

trait StateAssistant
{
    abstract protected function fieldsModel($id);

    public function routesStateAssistant(): array
    {
        return [
            ManagedRoute::get('status-window', '{id}/state/{key}/window'),
            ManagedRoute::get('state-edit', '{id}/state/{key}'),
            ManagedRoute::put('state-update', '{id}/state/{key}/{transitionKey}'),
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function stateEdit(Request $request, $id, $key)
    {
        $model = $this->fieldsModel($id);

        return view('chief-addon-state::edit', [
            'manager' => $this->registry->findManagerByModel($model->resourceKey()),
            'model' => $model,
            'stateConfig' => $model->getStateConfig($key),
        ]);
    }

    public function stateUpdate(Request $request, $id, $key, $transitionKey)
    {
        $model = $this->fieldsModel($id);
        $stateAdminConfig = $model->getStateAdminConfig($key);

        try {
            $stateMachine = $stateAdminConfig->getStateMachine();
            $stateMachine->apply($model, $transitionKey);

            $model->save();

            $stateMachine->emitEvent($model, $transitionKey);
        } catch (StateException $e) {
            //
        }

        // In case that the state makes the model inaccessible (such as a deletion)
        // we'll want to redirect to a different page.
        $redirect = $stateAdminConfig->redirectAfterTransition($transitionKey);

        if ($redirect && ! $request->expectsJson()) {
            return redirect()->to($redirect);
        }

        return response()->json([
            'message' => 'State applied',
            'redirect' => $redirect,
            'livewire' => ! ($redirect), // Dont reload livewire when we have a redirect
        ], 200);
    }
}
