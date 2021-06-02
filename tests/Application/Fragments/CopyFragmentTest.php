<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class CopyFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
    }

    /** @test */
    public function it_can_copy_a_static_fragment()
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
    public function by_default_a_dynamic_fragment_is_copied_as_reference()
    {
        $quote = $this->setupAndCreateQuote($this->owner);
        $newOwner = ArticlePage::create();

        $this->asAdmin()->post($this->manager($quote)->route('fragment-copy', $newOwner, $quote));

        $this->assertFragmentCount($newOwner, 1);

        $this->assertCount(1, Quote::all());

        $existingFragmentFresh = $this->firstFragment($this->owner);
        $fragmentFresh = $this->firstFragment($newOwner);

        $this->assertEquals($existingFragmentFresh->id, $fragmentFresh->id);
        $this->assertEquals($existingFragmentFresh->fragmentModel()->id, $fragmentFresh->fragmentModel()->id);
        $this->assertEquals($existingFragmentFresh->fragmentModel()->model_reference, $fragmentFresh->fragmentModel()->model_reference);
        $this->assertTrue($existingFragmentFresh->fragmentModel()->isShared());
        $this->assertTrue($fragmentFresh->fragmentModel()->isShared());
    }

    /** @test */
    public function static_nested_fragments_are_copied_when_fragment_is_copied()
    {
        $quote = $this->setupAndCreateQuote($this->owner);
        $newOwner = ArticlePage::create();
        $nestedFragment = $this->setupAndCreateSnippet($quote);

        $this->createAsFragment($nestedFragment, $quote->fragmentModel());

        $this->asAdmin()->post($this->manager($quote)->route('fragment-copy', $newOwner, $quote), [
            'hardcopy' => true,
        ]);

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

        $this->createAsFragment($nestedFragment, $quote->fragmentModel());

        $this->asAdmin()->post($this->manager($quote)->route('fragment-copy', $newOwner, $quote));

        $this->assertFragmentCount($newOwner, 1);

        $fragmentFresh = $this->firstFragment($newOwner);

        $existingNestedFragmentFresh = $this->firstFragment($quote->fragmentModel());
        $nestedFragmentFresh = $this->firstFragment($fragmentFresh->fragmentModel());

        $this->assertEquals($existingNestedFragmentFresh->id, $nestedFragmentFresh->id);
        $this->assertEquals($existingNestedFragmentFresh->fragmentModel()->id, $nestedFragmentFresh->fragmentModel()->id);
    }
}
