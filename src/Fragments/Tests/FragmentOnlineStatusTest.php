<?php

namespace Thinktomorrow\Chief\Fragments\Tests;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\Events\FragmentPutOffline;
use Thinktomorrow\Chief\Fragments\Events\FragmentPutOnline;
use Thinktomorrow\Chief\Fragments\FragmentStatus;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentOnlineStatusTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $fragment;
    private Manager $fragmentManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->owner);
        $this->fragmentManager = $this->manager($this->fragment);
    }

    /** @test */
    public function a_fragment_is_default_online()
    {
        $this->firstFragment($this->owner, function ($fragment) {
            $this->assertTrue($fragment->fragmentModel()->isOnline());
        });
    }

    /** @test */
    public function it_can_be_put_offline()
    {
        Event::fake();

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);
        $this->assertTrue($fragments->first()->fragmentModel()->isOnline());

        $this->asAdmin()->post($this->fragmentManager->route('fragment-status', $this->fragment), [
            'online_status' => FragmentStatus::offline->value,
        ]);

        $this->firstFragment($this->owner, function ($fragment) {
            $this->assertFalse($fragment->fragmentModel()->isOnline());
        });

        Event::assertDispatched(FragmentPutOffline::class);
    }

    /** @test */
    public function it_can_be_put_online()
    {
        Event::fake();

        $this->asAdmin()->post($this->fragmentManager->route('fragment-status', $this->fragment), [
            'online_status' => FragmentStatus::online->value,
        ]);

        $this->firstFragment($this->owner, function ($fragment) {
            $this->assertTrue($fragment->fragmentModel()->isOnline());
        });

        Event::assertDispatched(FragmentPutOnline::class);
    }

    /** @test */
    public function it_only_renders_children_that_are_online()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-status', $this->fragment), [
            'online_status' => FragmentStatus::offline->value,
        ]);

        $this->assertFragmentCount($this->owner, 1);
        $this->assertRenderedFragments($this->owner, "");
    }

    /** @test */
    public function it_renders_offline_children_when_admin_is_previewing()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-status', $this->fragment), [
            'online_status' => FragmentStatus::offline->value,
        ]);

        // We need a fake request for the Preview check to work because it is based on a frontend request.
        $this->get('/bar');

        session()->flash('preview-mode', true);

        $this->assertFragmentCount($this->owner, 1);
        $this->assertRenderedFragments($this->owner, "THIS IS QUOTE FRAGMENT\n");
    }

    /** @test */
    public function it_does_not_render_offline_children_when_admin_is_not_previewing()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-status', $this->fragment), [
            'online_status' => FragmentStatus::offline->value,
        ]);

        // We need a fake request for the Preview check to work because it is based on a frontend request.
        $this->get('/bar');

        session()->flash('preview-mode', false);

        $this->assertFragmentCount($this->owner, 1);
        $this->assertRenderedFragments($this->owner, "");
    }
}
