<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Viewable;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\NotFoundView;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithBaseSegments;

class ViewableTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['view']->addLocation(__DIR__ . '/../../../Shared/stubs/views');
    }

    /** @test */
    public function it_can_render_a_view()
    {
        $page = $this->setupAndCreateArticle();

        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n", $page->renderView());
    }

    /** @test */
    public function it_can_render_a_fragment_view()
    {
        $owner = $this->setupAndCreateArticle();
        $quote = $this->setupAndCreateQuote($owner);

        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\nTHIS IS QUOTE FRAGMENT\n", $owner->renderView());
    }

    /** @test */
    public function it_can_render_a_page_specific_fragment_view()
    {
        $this->setupAndCreateArticle();
        $owner = ArticlePageWithBaseSegments::create();
        $this->setupAndCreateQuote($owner);

        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\nTHIS IS ARTICLE SPECIFIC QUOTE FRAGMENT\n", $owner->renderView());
    }

    /** @test */
    public function it_throws_exception_when_view_isnt_found()
    {
        $this->expectException(NotFoundView::class);

        config()->set('chief.strict', true);

        $snippet = new SnippetStub();
        $snippet->renderView();
    }
}
