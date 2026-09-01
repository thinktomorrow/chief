<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface StateTransitionGuard
{
    public function assertCanTransition(StatefulContract $statefulContract, string $transition, array $data): void;
}
