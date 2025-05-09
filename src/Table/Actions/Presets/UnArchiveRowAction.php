<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class UnArchiveRowAction extends Action
{
    public static function makeDefault(string $resourceKey, string $stateKey = 'current_state', string $transitionKey = 'unarchive'): static
    {
        return static::make('unarchive-state-row')
            ->label('Herstel uit archief')
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
            ->notifyOnSuccess('Hersteld en opnieuw beschikbaar')->notifyOnFailure('Er is iets misgegaan bij het herstellen van dit item.')
            ->when(function ($model) use ($stateKey, $transitionKey) {

                if (! $model instanceof StatefulContract) {
                    return false;
                }

                $stateConfig = $model->getStateConfig($stateKey);
                $stateMachine = StateMachine::fromConfig($model, $stateConfig);

                // Works for page state and simple state
                return $stateMachine->can($transitionKey);
            });
    }
}
