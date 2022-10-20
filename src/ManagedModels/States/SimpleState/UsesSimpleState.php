<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\SimpleState;

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
        if ($key == SimpleState::KEY && $this->$key) {
            return SimpleState::from($this->$key);
        }

        return null;
    }

    public function changeState(string $key, State $state): void
    {
        $this->$key = $state->getValueAsString();
    }

    public function getStateConfig(string $stateKey): StateConfig
    {
        if ($stateKey == SimpleState::KEY) {
            return app(SimpleStateConfig::class);
        }

        throw new \InvalidArgumentException('No state config found for ' . $stateKey);
    }

    public function inOnlineState(): bool
    {
        return in_array($this->getState(SimpleState::KEY), [SimpleState::online]);
    }
}
