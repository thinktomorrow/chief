<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages\Traits;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class ArchiveTraitTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function it_can_check_if_the_model_is_archived()
    {
        $page = new ArticlePage(['current_state' => PageState::archived->getValueAsString()]);
        $this->assertTrue($page->isArchived());

        $page->changeState('current_state', PageState::draft);
        $this->assertFalse($page->isArchived());
    }

    /** @test */
    public function it_can_get_archived_pages()
    {
        ArticlePage::create(['current_state' => PageState::archived->getValueAsString()]);
        ArticlePage::create(['current_state' => PageState::archived->getValueAsString()]);
        ArticlePage::create(['current_state' => PageState::draft->getValueAsString()]);

        $this->assertCount(2, ArticlePage::archived()->get());
        $this->assertCount(1, ArticlePage::unarchived()->get());
    }
}
