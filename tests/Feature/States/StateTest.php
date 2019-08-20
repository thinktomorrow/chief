<?php

namespace Thinktomorrow\Chief\Tests\States;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\States\State\StateException;

class StateTest extends TestCase
{
    private $page;
    private $machine;

    public function setUp(): void
    {
        parent::setUp();

        $this->page = ProductPageFake::create()->fresh();

        $this->machine = new PageState($this->page);
    }

    /** @test */
    public function it_can_apply_transition()
    {
        $this->assertEquals('draft', $this->page->state());

        $this->machine->apply('publish');
        $this->assertEquals('published', $this->page->state());

        $this->machine->apply('archive');
        $this->assertEquals('archived', $this->page->state());
    }

    /** @test */
    public function it_cannot_change_to_invalid_state_directly()
    {
        $this->expectException(StateException::class);

        $this->page->changeState('foobar');
    }

    /** @test */
    public function it_ignores_change_to_current_state()
    {
        $this->assertEquals('draft', $this->page->state());
        $this->page->changeState('draft');
        $this->assertEquals('draft', $this->page->state());
    }

    /** @test */
    public function it_only_allows_transition_to_allowed_state()
    {
        $this->expectException(StateException::class);

        $this->machine->apply('delete');
    }

    /** @test */
    public function it_tells_when_page_is_offline()
    {
        $this->assertTrue($this->machine->isOffline());
        $this->assertFalse($this->machine->isOnline());

        $this->machine->apply('publish');

        $this->assertFalse($this->machine->isOffline());
        $this->assertTrue($this->machine->isOnline());
    }
}
