<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Viewable;

use Thinktomorrow\Chief\Shared\Concerns\Viewable\NotFoundView;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Viewless;

class ViewableTest extends ChiefTestCase
{
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
    public function it_throws_exception_when_view_isnt_found()
    {
        $this->expectException(NotFoundView::class);

        (new Viewless())->renderView();
    }
}
