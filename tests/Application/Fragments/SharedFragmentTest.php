<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Fragments\Actions\GetOwningModels;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\FragmentsComponentRepository;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class SharedFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $fragment;
    private Manager $fragmentManager;

    /** @var FragmentRepository */
    private $fragmentRepo;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->owner);
        $this->fragmentManager = $this->manager($this->fragment);

        $this->fragmentRepo = app(FragmentRepository::class);
    }

    /** @test */
    public function a_fragment_can_be_shared()
    {
        $owner2 = ArticlePage::create([]);
        $this->asAdmin()->post($this->fragmentManager->route('fragment-add', $owner2, $this->fragment));

        $this->assertCount(1, DB::table('context_fragments')->get());
        $this->assertCount(2, DB::table('context_fragment_lookup')->where('fragment_id', $this->fragment->fragmentModel()->id)->get());

        $ownerFragment = $this->fragmentRepo->getByOwner($this->owner)->first();
        $owner2Fragment = $this->fragmentRepo->getByOwner($owner2)->first();

        $this->assertEquals($ownerFragment->toArray(), $owner2Fragment->toArray());
        $this->assertEquals(Arr::except($ownerFragment->fragmentModel()->toArray(), 'pivot'), Arr::except($owner2Fragment->fragmentModel()->toArray(), 'pivot'));
    }

    /** @test */
    public function when_a_fragment_is_shared_is_it_flagged_as_shareable()
    {
        $this->assertFalse($this->fragment->fragmentModel()->isShared());

        $owner2 = ArticlePage::create([]);
        $this->asAdmin()->post($this->fragmentManager->route('fragment-add', $owner2, $this->fragment));

        $this->assertTrue($this->fragment->fragmentModel()->fresh()->isShared());
    }

    /** @test */
    public function it_can_retrieve_all_owning_models()
    {
        $owner2 = ArticlePage::create([]);
        $this->asAdmin()->post($this->fragmentManager->route('fragment-add', $owner2, $this->fragment));

        $owners = app(GetOwningModels::class)->get($this->fragment->fragmentModel());

        $this->assertCount(2, $owners);

        foreach ($owners as $owner) {
            $this->assertInstanceOf(ArticlePage::class, $owner['model']);
            $this->assertInstanceOf(Manager::class, $owner['manager']);
        }
    }

    /** @test */
    public function it_can_retrieve_all_shareable_fragments()
    {
        $this->setupAndCreateSnippet($this->owner);

        $sharedFragments = app()->makeWith(FragmentsComponentRepository::class, ['owner' => $this->owner])->getSharedFragments();

        $this->assertCount(2, $sharedFragments);
    }

    /** @test */
    public function it_can_retrieve_only_shareable_fragments_when_they_are_allowed_fragments()
    {
        $this->setupAndCreateHero(ArticlePage::create());

        $sharedFragments = app()->makeWith(FragmentsComponentRepository::class, ['owner' => $this->owner])->getSharedFragments();

        $this->assertCount(1, $sharedFragments);
    }

    /** @test */
    public function already_selected_fragments_are_marked_with_a_flag()
    {
        $this->setupAndCreateSnippet(ArticlePage::create());

        $sharedFragments = app()->makeWith(FragmentsComponentRepository::class, ['owner' => $this->owner])->getSharedFragments();

        $checks = 0;
        foreach ($sharedFragments as $sharedFragment) {
            if ($sharedFragment['model'] instanceof Quote) {
                $this->assertTrue($sharedFragment['is_already_selected']);
                $checks++;
            }
            if ($sharedFragment['model'] instanceof SnippetStub) {
                $this->assertFalse($sharedFragment['is_already_selected']);
                $checks++;
            }
        }
        $this->assertEquals(2, $checks); // assert all fragments are checked
    }
}
