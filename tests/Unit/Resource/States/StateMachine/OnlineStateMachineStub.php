<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\States\StateMachine;

use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;

class OnlineStateMachineStub extends StateMachine
{
    protected array $states = [
        true, // online
        false, // offline
    ];

    protected array $transitions = [
        'publish' => [
            'from' => [false],
            'to' => true,
        ],
        'unpublish' => [
            'from' => [true],
            'to' => false,
        ],
    ];
}
