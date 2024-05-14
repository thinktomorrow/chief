<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Domain;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class SharedFragmentTest extends ChiefTestCase
{
    private $owner;

    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        chiefRegister()->resource(ArticlePageResource::class);
        chiefRegister()->fragment(Quote::class);

        $this->owner = ArticlePage::create();
    }

    public function test_a_fragment_is_default_not_shared()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        $this->assertFalse($fragment->fragmentModel()->fresh()->isShared());
    }

    public function test_a_fragment_is_shared_when_attached_to_contexts_belonging_to_different_owners()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        $owner2 = ArticlePage::create();
        $context2 = FragmentTestAssist::findOrCreateContext($owner2);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertCount(1, DB::table('context_fragments')->get());
        $this->assertCount(2, DB::table('context_fragment_lookup')->where('fragment_id', $fragment->getFragmentId())->get());

        $this->assertTrue($fragment->fragmentModel()->fresh()->isShared());
    }

    public function test_a_fragment_is_not_considered_shared_when_attached_to_contexts_belonging_to_same_owner()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        $context2 = FragmentTestAssist::createContext($this->owner);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertCount(1, DB::table('context_fragments')->get());
        $this->assertCount(2, DB::table('context_fragment_lookup')->where('fragment_id', $fragment->getFragmentId())->get());

        $this->assertFalse($fragment->fragmentModel()->fresh()->isShared());
    }
}
