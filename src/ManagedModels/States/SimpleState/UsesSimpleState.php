<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\SimpleState;

use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\ManagedModels\States\State\State;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;

trait UsesSimpleState
{
    public function getStateKeys(): array
    {
        return [SimpleState::KEY];
    }

    /** @return SimpleState */
    public function getState(string $key): ?State
    {
        if (SimpleState::KEY == $key && $this->{$key}) {
            return SimpleState::from($this->{$key});
        }

        return null;
    }

    public function changeState(string $key, State $state): void
    {
        $this->{$key} = $state->getValueAsString();
    }

    public function getStateConfig(string $stateKey): StateConfig
    {
        if (SimpleState::KEY == $stateKey) {
            return app(SimpleStateConfig::class);
        }

        throw new \InvalidArgumentException('No state config found for '.$stateKey);
    }

    public function inOnlineState(): bool
    {
        return in_array($this->getState(SimpleState::KEY), $this->onlineStates());
    }

    /**
     * Eloquent builder scope for filtering out the online models.
     */
    public function scopeOnline(Builder $query): void
    {
        // Here we widen up the results in case of preview mode and ignore the published scope
        if (PreviewMode::fromRequest()->check()) {
            return;
        }

        $query->whereIn($this->getTable().'.'.$this->getStateAttribute(), $this->onlineStates());
    }

    protected function getStateAttribute(): string
    {
        return SimpleState::KEY;
    }

    protected function onlineStates(): array
    {
        return [
            SimpleState::online,
        ];
    }
}
