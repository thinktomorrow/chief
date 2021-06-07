<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;

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
    public function a_shared_fragment_is_copied_as_shared()
    {
        // Make snippet shared
        $snippet = $this->setupAndCreateSnippet($this->owner);
        $owner2 = ArticlePage::create([]);
        $this->asAdmin()->post($this->manager($snippet)->route('fragment-add', $owner2, $snippet));

        $newOwner = ArticlePage::create();

        $this->asAdmin()->post($this->manager($snippet)->route('fragment-copy', $newOwner, $snippet));

        $this->assertFragmentCount($newOwner, 1);

        $this->assertCount(1, FragmentModel::all());

        $existingFragmentFresh = $this->firstFragment($this->owner);
        $fragmentFresh = $this->firstFragment($newOwner);

        $this->assertEquals($existingFragmentFresh->fragmentModel()->id, $fragmentFresh->fragmentModel()->id);
        $this->assertEquals($existingFragmentFresh->fragmentModel()->model_reference, $fragmentFresh->fragmentModel()->model_reference);
        $this->assertTrue($existingFragmentFresh->fragmentModel()->isShared());
        $this->assertTrue($fragmentFresh->fragmentModel()->isShared());
    }

    /** @test */
    public function nested_fragments_are_also_copied()
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

        $this->assertNotEquals($existingNestedFragmentFresh->fragmentModel()->id, $nestedFragmentFresh->fragmentModel()->id);
    }

    /** @test */
    public function shared_nested_fragments_are_also_shared()
    {
        $snippet = $this->setupAndCreateSnippet($this->owner);

        // Make snippet shared
        $snippetNested = $this->setupAndCreateSnippet($this->owner);
        $this->asAdmin()->post($this->manager($snippetNested)->route('fragment-add', $snippet, $snippetNested));

        $newOwner = ArticlePage::create();
        $this->asAdmin()->post($this->manager($snippet)->route('fragment-copy', $newOwner, $snippet));

        $this->assertFragmentCount($newOwner, 1);

        $fragmentFresh = $this->firstFragment($newOwner);

        $existingNestedFragmentFresh = $this->firstFragment($snippet->fragmentModel());
        $nestedFragmentFresh = $this->firstFragment($fragmentFresh->fragmentModel());

        $this->assertEquals($existingNestedFragmentFresh->fragmentModel()->id, $nestedFragmentFresh->fragmentModel()->id);
    }
}
