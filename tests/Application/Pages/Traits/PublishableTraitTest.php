<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages\Traits;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class PublishableTraitTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function it_can_check_if_the_model_is_published()
    {
        $page = new ArticlePage(['current_state' => PageState::PUBLISHED]);
        $this->assertTrue($page->isPublished());
        $this->assertFalse($page->isDraft());

        $page->changeStateOf('current_state', PageState::DRAFT);
        $this->assertFalse($page->isPublished());
        $this->assertTrue($page->isDraft());
    }

    /** @test */
    public function it_can_get_all_the_published_models()
    {
        ArticlePage::create(['current_state' => PageState::PUBLISHED]);
        ArticlePage::create(['current_state' => PageState::PUBLISHED]);
        ArticlePage::create(['current_state' => PageState::DRAFT]);

        $this->assertCount(2, ArticlePage::published()->get());
        $this->assertCount(1, ArticlePage::drafted()->get());
    }
}
