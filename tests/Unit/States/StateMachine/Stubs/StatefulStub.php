<?php

namespace Thinktomorrow\Chief\Tests\Unit\States\StateMachine\Stubs;

use Thinktomorrow\Chief\ManagedModels\States\State\State;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

class StatefulStub implements StatefulContract
{
    public $online_state;

    public function __construct()
    {
        $this->online_state = OnlineStateStub::offline->getValueAsString();
    }

    public function getStateKeys(): array
    {
        return ['online', 'enabled'];
    }

    public function getState(string $key): ?State
    {
        return OnlineStateStub::from($this->$key);
    }

    public function changeState(string $key, State $state): void
    {
        $this->$key = $state->getValueAsString();
    }

    public function getStateConfig(string $stateKey): StateConfig
    {
        return new OnlineStateConfigStub();
    }

    public function inOnlineState(): bool
    {
        return $this->getState('online') == OnlineStateStub::online;
    }
}
