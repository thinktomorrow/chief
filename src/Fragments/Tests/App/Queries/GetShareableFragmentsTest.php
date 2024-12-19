<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Fragments\App\Queries\GetShareableFragments;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class GetShareableFragmentsTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private ArticlePage $owner2;
    private GetShareableFragments $query;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        $this->owner2 = ArticlePage::create();
        chiefRegister()->fragment(Quote::class);
        chiefRegister()->fragment(Hero::class);

        $this->query = app(GetShareableFragments::class);
    }

    public function test_it_gets_all_shareable_fragments_including_own()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        $context2 = FragmentTestAssist::createContext($this->owner2);
        $shareableFragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context2->id);

        $shareableFragments = $this->query->get($context->id);
        $this->assertCount(2, $shareableFragments);
    }

    public function test_already_selected_fragments_are_flagged()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id, 0);

        $context2 = FragmentTestAssist::createContext($this->owner2);
        $shareableFragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context2->id, 2);

        $shareableFragments = $this->query->get($context->id);

        $this->assertCount(2, $shareableFragments);

        // Sort it first so our tests don't fail
        $shareableFragments = $shareableFragments->sortBy(fn ($shareableFragment) => ! $shareableFragment->is_already_selected ? 0 : 1)->values();

        $this->assertEquals($shareableFragment->getFragmentId(), $shareableFragments[0]->getFragmentId());
        $this->assertFalse($shareableFragments[0]->is_already_selected);

        $this->assertEquals($fragment->getFragmentId(), $shareableFragments[1]->getFragmentId());
        $this->assertTrue($shareableFragments[1]->is_already_selected);

    }

    public function test_it_can_filter_by_type()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        FragmentTestAssist::createAndAttachFragment(Hero::class, $context->id);

        $this->assertCount(1, $this->query->get($context->id));
        $this->assertCount(0, $this->query->filterByTypes([
            Quote::resourceKey(),
        ])->get($context->id));
    }

    public function test_it_can_filter_by_owner()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        FragmentTestAssist::createAndAttachFragment(Hero::class, $context->id);

        $this->assertCount(1, $this->query->filterByOwners([
            $this->owner->modelReference()->get(),
        ])->get($context->id));
        $this->assertCount(0, $this->query->filterByOwners([
            $this->owner2->modelReference()->get(),
        ])->get($context->id));
    }

    public function test_it_can_exclude_current_fragments()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        $context2 = FragmentTestAssist::createContext($this->owner2);
        $shareableFragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context2->id);

        $this->assertCount(2, $this->query->get($context->id));
        $this->assertCount(1, $this->query->excludeAlreadySelected()->get($context->id));
    }

    public function test_it_can_limit_results()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        $context2 = FragmentTestAssist::createContext($this->owner2);
        $shareableFragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context2->id);

        $this->assertCount(2, $this->query->get($context->id));
        $this->assertCount(1, $this->query->limit(1)->get($context->id));
    }
}