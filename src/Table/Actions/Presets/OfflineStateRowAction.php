<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class OfflineStateRowAction extends Action
{
    public static function makeDefault(string $resourceKey, string $stateKey = 'current_state', string $transitionKey = 'unpublish'): static
    {
        return static::make('offline-state-row')
            ->label('Zet terug in draft')
            ->variant('red')
            ->prependIcon('<x-chief::icon.view-off-slash />')
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
            ->keepDialogOpen()
            ->notifyOnSuccess('Staat in draft')->notifyOnFailure('Er is iets misgegaan bij het in draft zetten.')
            ->when(function ($model) {
                return $model instanceof StatefulContract && $model->inOnlineState();
            });
    }
}
