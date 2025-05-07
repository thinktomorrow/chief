<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\State;

use Illuminate\Database\Eloquent\Builder;

interface StatefulContract
{
    /**
     * List of all state keys available on this model
     */
    public function getStateKeys(): array;

    public function getState(string $key): ?State;

    public function changeState(string $key, State $state): void;

    public function getStateConfig(string $stateKey): StateConfig;

    public function inOnlineState(): bool;

    public function scopePublished(Builder $query): void;
}
