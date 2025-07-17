<?php

namespace Thinktomorrow\Chief\Table\Actions\Presets;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\State\GetPrimaryStateKeyOfModel;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Table\Actions\Action;

class OnlineStateBulkAction extends Action
{
    public static function makeDefault(string $resourceKey, ?string $stateKey = null, string $transitionKey = 'publish'): static
    {
        if (! $primaryStateKey = GetPrimaryStateKeyOfModel::get($resourceKey)) {
            throw new \RuntimeException('OnlineStateBulkAction requires a primary state key to be defined on the model.');
        }

        $stateKey = $stateKey ?: $primaryStateKey;

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
