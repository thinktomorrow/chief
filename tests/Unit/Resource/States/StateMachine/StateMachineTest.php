<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\States\StateMachine;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Tests\Unit\Resource\States\StateMachine\Stubs\StatefulStub;
use Thinktomorrow\Chief\Tests\Unit\Resource\States\StateMachine\Stubs\OnlineStateStub;

class StateMachineTest extends TestCase
{
    private StatefulContract $statefulStub;
    private StateMachine $machine;

    public function setUp(): void
    {
        parent::setUp();

        $this->statefulStub = new StatefulStub();
        $this->machine = StateMachine::fromConfig($this->statefulStub, $this->statefulStub->getStateConfig('online_state'));
    }

    /** @test */
    public function it_can_apply_transition()
    {
        $this->assertSame(OnlineStateStub::offline, $this->statefulStub->getState('online_state'));

        $this->machine->apply('publish');
        $this->assertEquals(OnlineStateStub::online, $this->statefulStub->getState('online_state'));
    }

    /** @test */
    public function it_cannot_change_to_invalid_state()
    {
        $this->expectException(StateException::class);

        $this->machine->apply('foobar');
    }

    /** @test */
    public function it_ignores_change_to_current_state()
    {
        $this->assertSame(OnlineStateStub::offline, $this->statefulStub->getState('online_state'));
        $this->statefulStub->changeState('online_state', OnlineStateStub::online);
        $this->assertSame(OnlineStateStub::online, $this->statefulStub->getState('online_state'));
    }

    /** @test */
    public function it_only_allows_transition_to_allowed_state()
    {
        $this->expectException(StateException::class);

        $this->machine->apply('unpublish');
    }
}
