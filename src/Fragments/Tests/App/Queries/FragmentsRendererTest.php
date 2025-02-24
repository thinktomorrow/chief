<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Fragments\App\Queries\GetFragments;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentsRendererTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    public function test_it_can_render_fragments()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        $this->assertEquals("THIS IS QUOTE FRAGMENT\n", app(GetFragments::class)->render($context->id));
    }

    public function test_it_does_not_render_anything_by_default()
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $this->assertEquals('', app(GetFragments::class)->render($context->id));
    }
}
