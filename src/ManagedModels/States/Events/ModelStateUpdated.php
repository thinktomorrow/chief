<?php

namespace Thinktomorrow\Chief\ManagedModels\States\Events;

class ModelStateUpdated
{
    public function __construct(
        public readonly string $modelReference,
        public readonly string $stateKey,
        public readonly string $formerState,
        public readonly string $newState,
        public readonly string $transition,
    ) {}
}
