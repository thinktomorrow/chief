<?php

namespace Thinktomorrow\Chief\Tests\Unit\States\StateMachine;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\Tests\Unit\States\StateMachine\Stubs\MalformedStateConfigStub;
use Thinktomorrow\Chief\Tests\Unit\States\StateMachine\Stubs\MissingStateConfigStub;
use Thinktomorrow\Chief\Tests\Unit\States\StateMachine\Stubs\OnlineStateStub;
use Thinktomorrow\Chief\Tests\Unit\States\StateMachine\Stubs\StatefulStub;

class StateMachineIntegrityTest extends TestCase
{
    private $statefulStub;

    private $machine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->statefulStub = new StatefulStub;
        $this->machine = StateMachine::fromConfig($this->statefulStub, $this->statefulStub->getStateConfig('online_state'));
    }

    public function test_it_can_setup_machine()
    {
        $this->assertInstanceOf(StateMachine::class, $this->machine);
    }

    public function test_it_throws_exception_if_transition_map_is_malformed()
    {
        $this->expectException(StateException::class, 'malformed');

        StateMachine::fromConfig($this->statefulStub, new MalformedStateConfigStub);
    }

    public function test_it_throws_exception_if_transition_contains_invalid_state()
    {
        $this->expectException(StateException::class, 'non existing');

        StateMachine::fromConfig($this->statefulStub, new MissingStateConfigStub);
    }

    public function test_it_throws_exception_if_applying_unknown_transition()
    {
        $this->expectException(StateException::class, 'unknown transition [unknown] on Thinktomorrow\Trader\Unit\DummyStateMachine');

        $this->machine->apply('unknown');
    }

    public function test_it_throws_exception_if_applying_transition_is_disallowed()
    {
        $this->expectException(StateException::class, 'Transition [complete] cannot be applied from current state [new] on Thinktomorrow\Trader\Unit\DummyStateMachine');

        $this->machine->apply('complete');
    }

    public function test_it_can_apply_transition()
    {
        $this->assertEquals(OnlineStateStub::offline, $this->statefulStub->getState('online_state'));

        $this->machine->apply('publish');
        $this->assertEquals(OnlineStateStub::online, $this->statefulStub->getState('online_state'));
    }

    public function test_it_can_reset_same_state()
    {
        $this->expectException(StateException::class);

        $this->assertEquals(OnlineStateStub::offline, $this->statefulStub->getState('online_state'));
        $this->machine->apply('unpublish');

        $this->assertEquals(OnlineStateStub::offline, $this->statefulStub->getState('online_state'));
    }
}
