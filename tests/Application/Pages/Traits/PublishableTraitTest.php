<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages\Traits;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
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
        $page = new ArticlePage(['current_state' => PageState::published->getValueAsString()]);
        $this->assertTrue($page->isPublished());
        $this->assertFalse($page->isDraft());

        $page->changeState('current_state', PageState::draft);
        $this->assertFalse($page->isPublished());
        $this->assertTrue($page->isDraft());
    }

    /** @test */
    public function it_can_get_all_the_published_models()
    {
        ArticlePage::create(['current_state' => PageState::published->getValueAsString()]);
        ArticlePage::create(['current_state' => PageState::published->getValueAsString()]);
        ArticlePage::create(['current_state' => PageState::draft->getValueAsString()]);

        $this->assertCount(2, ArticlePage::published()->get());
        $this->assertCount(1, ArticlePage::drafted()->get());
    }
}
