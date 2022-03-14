<?php

namespace Thinktomorrow\Chief\Tests\Unit\ManagedModels\States;

use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;

class PageStateTest extends TestCase
{
    private $page;
    private $machine;

    public function setUp(): void
    {
        parent::setUp();

        $this->page = new ArticlePage(['current_state' => PageState::DRAFT]);
        $this->machine = PageState::make($this->page);
    }

    /** @test */
    public function it_can_apply_transition()
    {
        $this->assertEquals('draft', $this->page->stateOf(PageState::KEY));

        $this->machine->apply('publish');
        $this->assertEquals(PageState::PUBLISHED, $this->page->stateOf(PageState::KEY));

        $this->machine->apply('archive');
        $this->assertEquals(PageState::ARCHIVED, $this->page->stateOf(PageState::KEY));
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
        $this->assertEquals('draft', $this->page->stateOf(PageState::KEY));
        $this->page->changeStateOf(PageState::KEY, 'draft');
        $this->assertEquals('draft', $this->page->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_only_allows_transition_to_allowed_state()
    {
        $this->expectException(StateException::class);

        $this->machine->apply('unpublish');
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
