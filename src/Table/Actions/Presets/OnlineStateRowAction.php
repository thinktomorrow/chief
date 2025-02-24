<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class OnlineStateRowAction extends Action
{
    public static function makeDefault(string $resourceKey, string $stateKey = 'current_state', string $transitionKey = 'publish'): static
    {
        return static::make('online-state-row')
            ->label('Zet online')
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
            ->notifyOnSuccess('Staat nu online!')->notifyOnFailure('Er is iets misgegaan bij het online zetten van dit item.')
            ->when(function ($model) {
                return $model instanceof StatefulContract && ! $model->inOnlineState();
            });
    }
}
