<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Fragments\ActiveContextId;
use Thinktomorrow\Chief\Fragments\App\Queries\GetFragments;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\Tests\Stubs\RootFragmentStub;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class GetFragmentsTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    protected function tearDown(): void
    {
        ActiveContextId::clear();

        parent::tearDown();
    }

    public function test_it_can_render_fragments()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        $fragments = app(GetFragments::class)->get($context->id);

        $this->assertCount(1, $fragments);
        $this->assertEquals("THIS IS QUOTE FRAGMENT\n", $fragments->first()->render());
    }

    public function test_it_can_render_fragments_via_function()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        ActiveContextId::set($context->id);
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\nTHIS IS QUOTE FRAGMENT\n", $this->owner->renderView());
    }

    public function test_it_can_render_child_fragments()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $rootFragment = FragmentTestHelpers::createAndAttachFragment(RootFragmentStub::class, $context->id);
        FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id, $rootFragment->getFragmentId());

        ActiveContextId::set($context->id);
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\nTHIS IS ROOT FRAGMENT\n    THIS IS QUOTE FRAGMENT\n\n", $this->owner->renderView());
    }

    public function test_it_returns_empty_collection_when_no_fragments_are_found()
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $fragments = app(GetFragments::class)->get($context->id);

        $this->assertCount(0, $fragments);
    }
}
