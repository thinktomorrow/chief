<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Fragments\App\Queries\RenderFragments;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentsRendererTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    public function test_it_can_render_fragments()
    {
        $context = FragmentTestAssist::createContext($this->owner);
        FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        $this->assertEquals("THIS IS QUOTE FRAGMENT\n", app(RenderFragments::class)->render($context->id));
    }

    public function test_it_does_not_render_anything_by_default()
    {
        $context = FragmentTestAssist::createContext($this->owner);

        $this->assertEquals("", app(RenderFragments::class)->render($context->id));
    }
}
