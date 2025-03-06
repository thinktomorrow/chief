<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Domain;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class SharedFragmentTest extends ChiefTestCase
{
    private $owner;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);
        chiefRegister()->fragment(Quote::class);

        $this->owner = ArticlePage::create();
    }

    public function test_a_fragment_is_default_not_shared()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        $this->assertFalse($fragment->getFragmentModel()->fresh()->isShared());
    }

    public function test_a_fragment_is_shared_when_attached_to_contexts_belonging_to_different_owners()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        $owner2 = ArticlePage::create();
        $context2 = FragmentTestHelpers::findOrCreateContext($owner2);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertCount(1, DB::table('context_fragments')->get());
        $this->assertCount(2, DB::table('context_fragment_tree')->where('child_id', $fragment->getFragmentId())->get());

        $this->assertTrue($fragment->getFragmentModel()->fresh()->isShared());
    }

    public function test_a_fragment_is_not_considered_shared_when_attached_to_contexts_belonging_to_same_owner()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        $context2 = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertCount(1, DB::table('context_fragments')->get());
        $this->assertCount(2, DB::table('context_fragment_tree')->where('child_id', $fragment->getFragmentId())->get());

        $this->assertFalse($fragment->getFragmentModel()->fresh()->isShared());
    }
}
