<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface StatefulContract
{
    /**
     * List of all state keys available on this model
     * @return array
     */
    public function getStateKeys(): array;

    public function getState(string $key): ?State;

    public function changeState(string $key, State $state): void;

    public function getStateConfig(string $stateKey): StateConfig;
}
