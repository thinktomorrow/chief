<?php

namespace Thinktomorrow\Chief\Tests\Feature\States\StateMachine;

use Thinktomorrow\Chief\States\State\StateMachine;

class OnlineStateMachineStub extends StateMachine
{
    protected $states = [
        true, // online
        false, // offline
    ];

    protected $transitions = [
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
