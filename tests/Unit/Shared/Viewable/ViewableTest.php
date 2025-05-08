<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Viewable;

use Thinktomorrow\Chief\Fragments\ActiveContextId;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\NotFoundView;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Viewless;

class ViewableTest extends ChiefTestCase
{
    public function test_it_can_render_a_view()
    {
        $page = $this->setupAndCreateArticle();

        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n", $page->renderView());
    }

    public function test_it_can_render_fragments_of_active_context()
    {
        $owner = $this->setupAndCreateArticle();
        FragmentTestHelpers::createContextAndAttachFragment($owner, Quote::class, null, 0, ['custom' => 'foobar']);

        ActiveContextId::set(ContextModel::first()->id);

        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n    THIS IS QUOTE FRAGMENT\n\n", $owner->renderView()->render());
    }

    public function test_it_does_not_render_fragments_of_nonactive_context()
    {
        $owner = $this->setupAndCreateArticle();
        FragmentTestHelpers::createContextAndAttachFragment($owner, Quote::class, null, 0, ['custom' => 'foobar']);

        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n", $owner->renderView()->render());
    }

    public function test_it_throws_exception_when_view_isnt_found()
    {
        $this->expectException(NotFoundView::class);

        (new Viewless)->renderView();
    }
}
