<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class OnlineStateBulkAction extends Action
{
    public static function makeDefault(string $resourceKey, string $stateKey = 'current_state', string $transitionKey = 'publish'): static
    {
        return static::make('online-state-bulk')
            ->label('Publiceer')
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

                // TODO: allow to pass effect data to notification to show amount of fails...
                //                if(count($failedModelIds) > 0) {
                //                    return false;
                //                }

                return true;
            })
            ->notifyOnSuccess('De selectie is gepubliceerd!')->notifyOnFailure('Er is iets misgegaan bij het publiceren.');
    }
}
