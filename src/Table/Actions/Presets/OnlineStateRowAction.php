<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\GetPrimaryStateKeyOfModel;
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
            ->label('Publiceer de pagina')
            ->variant('green')
            ->prependIcon('<x-chief::icon.view />')
            ->effect(function ($formData, $data) use ($resourceKey, $stateKey, $transitionKey) {

                app(UpdateState::class)->handle(
                    $resourceKey,
                    ModelReference::fromString($data['item']),
                    $stateKey,
                    $transitionKey,
                    []
                );

                return true;
            })
            ->notifyOnSuccess('Is nu gepubliceerd!')->notifyOnFailure('Er is iets misgegaan bij het publiceren.')
            ->when(function ($component, $model) use ($stateKey, $transitionKey) {

                $stateConfig = $model->getStateConfig($stateKey);
                $stateMachine = StateMachine::fromConfig($model, $stateConfig);

                // Works for page state and simple state
                return $stateMachine->can($transitionKey);
            });
    }
}
