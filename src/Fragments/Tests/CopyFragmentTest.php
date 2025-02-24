<?php

namespace Thinktomorrow\Chief\Fragments\Tests;

use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class CopyFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_copy_a_static_fragment()
    {
        $snippet = $this->setupAndCreateSnippet($this->owner);
        $newOwner = ArticlePage::create();

        $this->asAdmin()->post($this->manager($snippet)->route('fragment-copy', $newOwner, $snippet));

        $this->assertFragmentCount($newOwner, 1);

        $existingFragmentFresh = $this->firstFragment($this->owner);
        $fragmentFresh = $this->firstFragment($newOwner);

        $this->assertNotEquals($existingFragmentFresh->fragmentModel()->id, $fragmentFresh->fragmentModel()->id);
        $this->assertEquals($existingFragmentFresh->fragmentModel()->key, $fragmentFresh->fragmentModel()->key);
    }

    public function test_a_shared_fragment_is_copied_as_shared()
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
        $this->assertEquals($existingFragmentFresh->fragmentModel()->key, $fragmentFresh->fragmentModel()->key);
        $this->assertTrue($existingFragmentFresh->fragmentModel()->isShared());
        $this->assertTrue($fragmentFresh->fragmentModel()->isShared());
    }

    public function test_nested_fragments_are_also_copied()
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

    public function test_shared_nested_fragments_are_also_shared()
    {
        $snippet = $this->setupAndCreateSnippet($this->owner);

        // Make snippet shared
        $snippetNested = $this->createAsFragment(new SnippetStub, $this->owner);
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
