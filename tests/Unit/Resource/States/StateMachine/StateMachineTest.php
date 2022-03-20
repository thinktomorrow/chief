<?php

namespace Thinktomorrow\Chief\Tests\Unit\Resource\States\StateMachine;

use PHPUnit\Framework\TestCase;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;

class StateMachineTest extends TestCase
{
    private $onlineStateMachine;

    /** @var StatefulStub */
    private $statefulStub;

    public function setUp(): void
    {
        parent::setUp();

        $this->statefulStub = new StatefulStub();
        $this->onlineStateMachine = new OnlineStateMachineStub($this->statefulStub, 'online');
    }

    /** @test */
    public function it_can_apply_transition()
    {
        $this->assertSame(false, $this->statefulStub->stateOf(StatefulStub::ONLINE_STATEKEY));

        $this->onlineStateMachine->apply('publish');
        $this->assertEquals(true, $this->statefulStub->stateOf(StatefulStub::ONLINE_STATEKEY));
    }

    /** @test */
    public function it_cannot_change_to_invalid_state()
    {
        $this->expectException(StateException::class);

        $this->onlineStateMachine->apply('foobar');
    }

    /** @test */
    public function it_ignores_change_to_current_state()
    {
        $this->assertSame(false, $this->statefulStub->stateOf(StatefulStub::ONLINE_STATEKEY));
        $this->statefulStub->changeStateOf(StatefulStub::ONLINE_STATEKEY, true);
        $this->assertSame(true, $this->statefulStub->stateOf(StatefulStub::ONLINE_STATEKEY));
    }

    /** @test */
    public function it_only_allows_transition_to_allowed_state()
    {
        $this->expectException(StateException::class);

        $this->onlineStateMachine->apply('unpublish');
    }
}
