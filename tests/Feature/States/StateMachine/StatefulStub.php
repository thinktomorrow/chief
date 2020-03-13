<?php

namespace Thinktomorrow\Chief\Tests\Feature\States\StateMachine;

use Thinktomorrow\Chief\States\State\StatefulContract;

class StatefulStub implements StatefulContract
{
    const ONLINE_STATEKEY = 'online';
    const BUYABLE_STATEKEY = 'buy_status';

    private $online = false;
    private $enabled = false;

    public function __construct()
    {
        //
    }

    public function stateOf(string $key)
    {
        return $this->$key;
    }

    public function changeStateOf(string $key, $state)
    {
        $this->$key = $state;
    }
}
