<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\PageState;

use Thinktomorrow\Chief\ManagedModels\States\State\State;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;

trait UsesPageState
{
    public function getStateKeys(): array
    {
        return [PageState::KEY];
    }

    /** @return PageState */
    public function getState(string $key): ?State
    {
        if($key == PageState::KEY && $this->$key) {
            return PageState::from($this->$key);
        }

        return null;
    }

    public function changeState(string $key, State $state): void
    {
        $this->$key = $state->getValueAsString();
    }

    public function getStateConfig(string $stateKey): StateConfig
    {
        if($stateKey == PageState::KEY) {
            return app(PageStateConfig::class);
        }

        throw new \InvalidArgumentException('No state config found for ' . $stateKey);
    }

    public function inOnlineState(): bool
    {
        return in_array($this->getState(PageState::KEY), [PageState::published]);
    }
}
