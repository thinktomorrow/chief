<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\GetPrimaryStateKeyOfModel;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class OnlineStateRowAction extends Action
{
    public static function makeDefault(string $resourceKey, ?string $stateKey = null, string $transitionKey = 'publish'): static
    {
        if (! $primaryStateKey = GetPrimaryStateKeyOfModel::get($resourceKey)) {
            throw new \RuntimeException('OnlineStateRowAction requires a primary state key to be defined on the model.');
        }

        $stateKey = $stateKey ?: $primaryStateKey;

        return static::make('online-state-row')
            ->label('Zet online')
            ->variant('green')
            ->prependIcon('<x-chief::icon.view />')
            ->effect(function ($formData, $data, Action $action) use ($resourceKey, $stateKey, $transitionKey) {

                try {
                    app(UpdateState::class)->handle(
                        $resourceKey,
                        ModelReference::fromString($data['item']),
                        $stateKey,
                        $transitionKey,
                        []
                    );
                } catch (StateException $e) {
                    $action->notifyOnFailure($e->getMessage());

                    return false;
                }

                return true;
            })
            ->notifyOnSuccess('Is nu online!')->notifyOnFailure('Er is iets misgegaan bij het zetten naar online.')
            ->when(function ($component, $model) use ($stateKey, $transitionKey) {

                $stateConfig = $model->getStateConfig($stateKey);
                $stateMachine = StateMachine::fromConfig($model, $stateConfig);

                // Works for page state and simple state
                return $stateMachine->can($transitionKey);
            });
    }
}
