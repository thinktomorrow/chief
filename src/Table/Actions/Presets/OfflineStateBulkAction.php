<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class OfflineStateBulkAction extends Action
{
    public static function makeDefault(string $resourceKey, string $stateKey = 'current_state', string $transitionKey = 'unpublish'): static
    {
        return static::make('offline-state-bulk')
            ->label('Zet terug in draft')
            ->effect(function ($formData, $data) use ($resourceKey, $stateKey, $transitionKey) {
                $modelIds = $data['items'];
                $failedModelIds = [];

                foreach ($modelIds as $modelId) {
                    try {
                        app(UpdateState::class)->handle(
                            $resourceKey,
                            ModelReference::fromString($resourceKey.'@'.$modelId),
                            $stateKey,
                            $transitionKey,
                            []
                        );
                    } catch (StateException $e) {
                        $failedModelIds[] = $modelId;
                    }
                }

                return true;
            })
            ->notifyOnSuccess('De selectie staat in draft!')
            ->notifyOnFailure('Er is iets misgegaan bij het in draft zetten.');
    }
}
