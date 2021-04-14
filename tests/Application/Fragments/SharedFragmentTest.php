<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
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
    public function a_fragment_can_be_flagged_as_shareable()
    {
        $this->assertFalse($this->fragment->fragmentModel()->isShared());

        $this->asAdmin()->post($this->fragmentManager->route('fragment-share', $this->fragment));

        $this->assertTrue($this->fragment->fragmentModel()->fresh()->isShared());
    }

    /** @test */
    public function a_fragment_can_be_dropped_as_shareable()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-share', $this->fragment));
        $this->asAdmin()->post($this->fragmentManager->route('fragment-unshare', $this->fragment));

        $this->assertFalse($this->fragment->fragmentModel()->fresh()->isShared());
    }

    /** @test */
    public function a_fragment_can_be_shared_by_multiple_owners()
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
}
