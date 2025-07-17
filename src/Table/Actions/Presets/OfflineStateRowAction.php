<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\GetPrimaryStateKeyOfModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class OfflineStateRowAction extends Action
{
    public static function makeDefault(string $resourceKey, ?string $stateKey = null, string $transitionKey = 'unpublish'): static
    {
        if (! $primaryStateKey = GetPrimaryStateKeyOfModel::get($resourceKey)) {
            throw new \RuntimeException('OfflineStateRowAction requires a primary state key to be defined on the model.');
        }

        $stateKey = $stateKey ?: $primaryStateKey;

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
            ->when(function ($component, $model) {
                return $model->inOnlineState();
            });
    }
}
