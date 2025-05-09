<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Fragments\App\Queries\GetShareableFragments;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class GetShareableFragmentsTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private ArticlePage $otherOwner;

    private GetShareableFragments $query;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        $this->otherOwner = ArticlePage::create();

        chiefRegister()->fragment(Hero::class);

        $this->query = app(GetShareableFragments::class);
    }

    public function test_it_returns_all_fragments_across_contexts()
    {
        $context1 = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment1 = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context1->id);

        $context2 = FragmentTestHelpers::createContext($this->otherOwner);
        $fragment2 = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context2->id);

        $results = $this->query->get($context1->id);

        $this->assertCount(2, $results);
        $this->assertEqualsCanonicalizing(
            [$fragment1->getFragmentId(), $fragment2->getFragmentId()],
            $results->map(fn ($fragment) => $fragment->getFragmentId())->all()
        );
    }

    public function test_it_marks_already_selected_fragments()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $selectedFragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);

        $otherContext = FragmentTestHelpers::createContext($this->otherOwner);
        $unselectedFragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $otherContext->id);

        $results = $this->query->get($context->id);

        $this->assertTrue($results->firstWhere(fn ($fragment) => $fragment->getFragmentId() == $selectedFragment->getFragmentId())->is_already_selected);
        $this->assertFalse($results->firstWhere(fn ($fragment) => $fragment->getFragmentId() == $unselectedFragment->getFragmentId())->is_already_selected);
    }

    public function test_it_filters_fragments_by_type()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);

        $results = $this->query
            ->filterByTypes(['non-existent-type']) // Ensure this doesn't match Hero
            ->get($context->id);

        $this->assertCount(0, $results);
    }

    public function test_it_filters_fragments_by_owner()
    {
        $context1 = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment1 = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context1->id);

        $context2 = FragmentTestHelpers::createContext($this->otherOwner);
        FragmentTestHelpers::createAndAttachFragment(Hero::class, $context2->id);

        $results = $this->query
            ->filterByOwners([$this->owner->modelReference()->get()])
            ->get($context1->id);

        $this->assertCount(1, $results);
        $this->assertEquals($fragment1->getFragmentId(), $results->first()->getFragmentId());
    }

    public function test_it_can_exclude_fragments_already_used_in_context()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $alreadyUsed = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);

        $otherContext = FragmentTestHelpers::createContext($this->otherOwner);
        $newFragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $otherContext->id);

        $results = $this->query
            ->excludeAlreadySelected()
            ->get($context->id);

        $this->assertCount(1, $results);
        $this->assertEquals($newFragment->getFragmentId(), $results->first()->getFragmentId());
    }

    public function test_it_limits_result_count()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);

        // Add multiple fragments
        FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);
        $context2 = FragmentTestHelpers::createContext($this->otherOwner);
        FragmentTestHelpers::createAndAttachFragment(Hero::class, $context2->id);

        $results = $this->query->limit(1)->get($context->id);

        $this->assertCount(1, $results);
    }

    public function test_it_excludes_non_root_fragments_by_default()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $parent = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id, null);
        $child = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id, $parent->getFragmentId());

        $results = $this->query->get($context->id);

        $this->assertTrue($results->contains(fn ($f) => $f->getFragmentId() === $parent->getFragmentId()));
        $this->assertFalse($results->contains(fn ($f) => $f->getFragmentId() === $child->getFragmentId()));
    }

    public function test_it_can_include_non_root_fragments()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $parent = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id, null);
        $child = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id, $parent->getFragmentId());

        $results = $this->query
            ->includeNonRootFragments()
            ->get($context->id);

        $this->assertTrue($results->contains(fn ($f) => $f->getFragmentId() === $parent->getFragmentId()));
        $this->assertTrue($results->contains(fn ($f) => $f->getFragmentId() === $child->getFragmentId()));
    }

    public function test_it_filters_by_shared_meta()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $unshared = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);
        $shared = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);
        $shared->getFragmentModel()->setMeta('shared', true);
        $shared->getFragmentModel()->save();

        $results = $this->query
            ->filterByShared()
            ->get($context->id);

        $this->assertTrue($results->contains(fn ($f) => $f->getFragmentId() === $shared->getFragmentId()));
        $this->assertFalse($results->contains(fn ($f) => $f->getFragmentId() === $unshared->getFragmentId()));
    }
}
