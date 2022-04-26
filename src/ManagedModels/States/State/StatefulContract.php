<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface StatefulContract
{
    public function getState(string $key);

    public function changeState(string $key, $state): void;

    public function getStateConfig(string $stateKey): StateConfig;
}
