<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class DuplicateFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
    }

    /** @test */
    public function it_can_duplicate_a_static_fragment()
    {
        $snippet = $this->setupAndCreateSnippet($this->owner);
        $newOwner = ArticlePage::create();

        $this->asAdmin()->post($this->manager($snippet)->route('fragment-copy', $newOwner, $snippet));

        $this->assertFragmentCount($newOwner, 1);

        $existingFragmentFresh = $this->firstFragment($this->owner);
        $fragmentFresh = $this->firstFragment($newOwner);

        $this->assertNotEquals($existingFragmentFresh->fragmentModel()->id, $fragmentFresh->fragmentModel()->id);
        $this->assertEquals($existingFragmentFresh->fragmentModel()->model_reference, $fragmentFresh->fragmentModel()->model_reference);
    }

    /** @test */
    public function a_fragment_can_be_duplicated()
    {
        $quote = $this->setupAndCreateQuote($this->owner);
        $newOwner = ArticlePage::create();

        $this->asAdmin()->post($this->manager($quote)->route('fragment-copy', $newOwner, $quote));

        $this->assertFragmentCount($newOwner, 1);

        $this->assertCount(2, Quote::all());

        $existingFragmentFresh = $this->firstFragment($this->owner);
        $fragmentFresh = $this->firstFragment($newOwner);

        $this->assertNotEquals($existingFragmentFresh->fragmentModel()->id, $fragmentFresh->fragmentModel()->id);
        $this->assertNotEquals($existingFragmentFresh->fragmentModel()->model_reference, $fragmentFresh->fragmentModel()->model_reference);
        $this->assertNotEquals($existingFragmentFresh->id, $fragmentFresh->id);
    }

    /** @test */
    public function static_nested_fragments_are_duplicated_as_well()
    {
        $quote = $this->setupAndCreateQuote($this->owner);
        $newOwner = ArticlePage::create();
        $nestedFragment = $this->setupAndCreateSnippet($quote);

        $this->addAsFragment($nestedFragment, $quote->fragmentModel());

        $this->asAdmin()->post($this->manager($quote)->route('fragment-copy', $newOwner, $quote));

        $this->assertFragmentCount($newOwner, 1);

        $fragmentFresh = $this->firstFragment($newOwner);

        $existingNestedFragmentFresh = $this->firstFragment($quote->fragmentModel());
        $nestedFragmentFresh = $this->firstFragment($fragmentFresh->fragmentModel());

        $this->assertTrue($nestedFragmentFresh->fragmentModel()->refersToStaticObject());
        $this->assertNotEquals($existingNestedFragmentFresh->fragmentModel()->id, $nestedFragmentFresh->fragmentModel()->id);
    }

    /** @test */
    public function dynamic_nested_fragments_are_not_duplicated_but_shared()
    {
        $quote = $this->setupAndCreateQuote($this->owner);
        $newOwner = ArticlePage::create();
        $nestedFragment = ArticlePage::create();

        $this->addAsFragment($nestedFragment, $quote->fragmentModel());

        $this->asAdmin()->post($this->manager($quote)->route('fragment-copy', $newOwner, $quote));

        $this->assertFragmentCount($newOwner, 1);

        $fragmentFresh = $this->firstFragment($newOwner);

        $existingNestedFragmentFresh = $this->firstFragment($quote->fragmentModel());
        $nestedFragmentFresh = $this->firstFragment($fragmentFresh->fragmentModel());

        $this->assertEquals($existingNestedFragmentFresh->id, $nestedFragmentFresh->id);
        $this->assertEquals($existingNestedFragmentFresh->fragmentModel()->id, $nestedFragmentFresh->fragmentModel()->id);
    }

    /** @test */
    public function a_nested_shared_fragment_is_kept_shared()
    {
        $this->disableExceptionHandling();

        $quote = $this->setupAndCreateQuote($this->owner);

        $newOwner = ArticlePage::create();

        $nestedFragment = $this->addAsFragment(ArticlePage::create(), $quote->fragmentModel());
        $this->asAdmin()->post($this->manager($quote)->route('fragment-share', $nestedFragment));

        $this->asAdmin()->post($this->manager($quote)->route('fragment-copy', $newOwner, $quote));

        $fragmentFresh = $this->firstFragment($newOwner);
        $nestedFragmentFresh = $this->firstFragment($fragmentFresh->fragmentModel());

        $this->assertTrue($nestedFragmentFresh->fragmentModel()->isShared());
    }
}
