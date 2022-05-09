<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\States\StateMachine\Stubs;

use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

class OnlineStateConfigStub implements StateConfig
{
    public function __construct()
    {
    }

    public function getStateKey(): string
    {
        return 'online_state';
    }

    public function getStates(): array
    {
        return [
            OnlineStateStub::online,
            OnlineStateStub::offline, // offline
        ];
    }

    public function getTransitions(): array
    {
        return [
            'publish' => [
                'from' => [OnlineStateStub::offline],
                'to' => OnlineStateStub::online,
            ],
            'unpublish' => [
                'from' => [OnlineStateStub::online],
                'to' => OnlineStateStub::offline,
            ],
        ];
    }

    public function emitEvent(StatefulContract $statefulContract, string $transition, array $data): void
    {
        // TODO: Implement emitEvent() method.
    }

    public function getStateLabel(StatefulContract $statefulContract): ?string
    {
        // TODO: Implement getStateLabel() method.
    }
}
