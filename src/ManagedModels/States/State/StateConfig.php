<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

interface StateConfig
{
    /**
     * The unique identifier of this state.
     * This usually is also the column name that refers to the current state in db.
     */
    public function getStateKey(): string;

    /**
     * @return State[]
     */
    public function getStates(): array;

    public function getTransitions(): array;

    public function emitEvent(StatefulContract $statefulContract, string $transition, array $data): void;
}
