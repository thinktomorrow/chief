<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

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
    public function it_can_share_a_fragment()
    {
        $this->assertFalse($this->fragment->fragmentModel()->isShared());

        $this->asAdmin()->post($this->fragmentManager->route('fragment-share', $this->fragment));

        $this->assertTrue($this->fragment->fragmentModel()->fresh()->isShared());
    }

    /** @test */
    public function it_can_unshare_a_fragment()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-share', $this->fragment));
        $this->asAdmin()->post($this->fragmentManager->route('fragment-unshare', $this->fragment));

        $this->assertFalse($this->fragment->fragmentModel()->fresh()->isShared());
    }

    /** @test */
    public function a_fragment_can_be_shared_by_multiple_owners()
    {
        $this->disableExceptionHandling();
        $owner2 = ArticlePage::create([]);

        $this->asAdmin()->post($this->fragmentManager->route('fragment-add', $owner2, $this->fragment));

        $this->assertCount(2, DB::table('context_fragment_lookup')->where('fragment_id', $this->fragment->fragmentModel()->id)->get());

        $ownerFragment = $this->fragmentRepo->getByOwner($this->owner)->first();
        $owner2Fragment = $this->fragmentRepo->getByOwner($this->owner)->first();
        $this->assertEquals($ownerFragment, $owner2Fragment);
    }
}
