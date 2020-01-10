<?php

namespace Thinktomorrow\Chief\Tests\Feature\States\StateMachine;

use Thinktomorrow\Chief\States\State\StateMachine;

class BuyableStateMachineStub extends StateMachine
{
    public function __construct(StatefulStub $statefulStub)
    {
        parent::__construct($statefulStub, 'buy_status');
    }
}
