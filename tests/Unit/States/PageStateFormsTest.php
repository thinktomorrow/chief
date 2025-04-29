<?php

namespace Thinktomorrow\Chief\Tests\Unit\States;

use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\State\StateException;
use Thinktomorrow\Chief\ManagedModels\States\State\StateMachine;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class PageStateFormsTest extends FormsTestCase
{
    private $page;

    private $machine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = new ArticlePage(['current_state' => PageState::draft->getValueAsString()]);
        $this->machine = StateMachine::fromConfig($this->page, $this->page->getStateConfig(PageState::KEY));
    }

    public function test_it_can_apply_transition()
    {
        $this->assertEquals(PageState::draft, $this->page->getState(PageState::KEY));

        $this->machine->apply('publish');
        $this->assertEquals(PageState::published, $this->page->getState(PageState::KEY));

        $this->machine->apply('archive');
        $this->assertEquals(PageState::archived, $this->page->getState(PageState::KEY));
    }

    public function test_it_cannot_change_to_invalid_state()
    {
        $this->expectException(StateException::class);

        $this->machine->apply('foobar');
    }

    public function test_it_ignores_change_to_current_state()
    {
        $this->assertEquals(PageState::draft, $this->page->getState(PageState::KEY));
        $this->page->changeState(PageState::KEY, PageState::draft);
        $this->assertEquals(PageState::draft, $this->page->getState(PageState::KEY));
    }

    public function test_it_only_allows_transition_to_allowed_state()
    {
        $this->expectException(StateException::class);

        $this->machine->apply('unpublish');
    }
}
