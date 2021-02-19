<?php

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels\States\StateMachine;

use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class StateMachineIntegrityTest extends ChiefTestCase
{
    private $dummyStatefulContract;
    private $machine;

    public function setUp(): void
    {
        parent::setUp();

        $this->dummyStatefulContract = new dummyStatefulContract();
        $this->machine = new DummyStateMachine($this->dummyStatefulContract, 'current_state');
    }

    /** @test */
    public function it_can_setup_machine()
    {
        $this->assertInstanceOf(StateMachine::class, $this->machine);
    }

    /** @test */
    public function it_throws_exception_if_transition_map_is_malformed()
    {
        $this->expectException(StateException::class, 'malformed');

        new MalformedStateMachine($this->dummyStatefulContract, 'current_state');
    }

    /** @test */
    public function it_throws_exception_if_transition_contains_invalid_state()
    {
        $this->expectException(StateException::class, 'non existing');

        new MissingStateMachine($this->dummyStatefulContract, 'current_state');
    }

    /** @test */
    public function it_throws_exception_if_applying_unknown_transition()
    {
        $this->expectException(StateException::class, 'unknown transition [unknown] on Thinktomorrow\Trader\Unit\DummyStateMachine');

        $this->machine->apply('unknown');
    }

    /** @test */
    public function it_throws_exception_if_applying_transition_is_disallowed()
    {
        $this->expectException(StateException::class, 'Transition [complete] cannot be applied from current state [new] on Thinktomorrow\Trader\Unit\DummyStateMachine');

        $this->machine->apply('complete');
    }

    /** @test */
    public function it_can_apply_transition()
    {
        $dummyStatefulContract = new dummyStatefulContract();
        $machine = new DummyStateMachine($dummyStatefulContract, 'current_state');

        $this->assertEquals('new', $dummyStatefulContract->stateOf('current_state'));

        $machine->apply('create');
        $this->assertEquals('pending', $dummyStatefulContract->stateOf('current_state'));
    }

    /** @test */
    public function it_can_reset_same_state()
    {
        $dummyStatefulContract = new dummyStatefulContract();
        $machine = new DummyStateMachine($dummyStatefulContract, 'current_state');

        $this->assertEquals('new', $dummyStatefulContract->stateOf('current_state'));
        $dummyStatefulContract->changeStateOf('current_state', 'new');
        $this->assertEquals('new', $dummyStatefulContract->stateOf('current_state'));
    }
}

class DummyStateMachine extends StateMachine
{
    protected $states = [
        'new',
        'pending',
        'completed',
        'canceled',
        'refunded',
    ];

    protected $transitions = [
        'create' => [
            'from' => ['new'],
            'to' => 'pending',
        ],
        'complete' => [
            'from' => ['pending'],
            'to' => 'completed',
        ],
    ];
}

class MalformedStateMachine extends StateMachine
{
    protected $transitions = [
        'complete' => [
            'from' => 'foobar',
        ],
    ];
}

class MissingStateMachine extends StateMachine
{
    protected $states = [
        'new',
    ];

    protected $transitions = [
        'create' => [
            'from' => ['new'],
            'to' => 'pending',
        ],
    ];
}

class DummyStatefulContract implements StatefulContract
{
    const STATE_NEW = 'new';
    const STATE_PENDING = 'pending';

    private $currentState;

    public function __construct($state = null)
    {
        // Default state
        $this->currentState = self::STATE_NEW;
    }

    public function stateOf($key): string
    {
        return $this->currentState;
    }

    public function changeStateOf($key, $state)
    {
        $this->currentState = $state;
    }
}
