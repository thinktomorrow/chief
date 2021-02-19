<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\FragmentsRenderer;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentOnlineStatusTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $fragment;
    private \Thinktomorrow\Chief\Managers\Manager $fragmentManager;

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
        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);
        $this->assertTrue($fragments->first()->fragmentModel()->isOnline());
    }

    /** @test */
    public function it_can_be_put_offline()
    {
        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);
        $this->assertTrue($fragments->first()->fragmentModel()->isOnline());

        $this->asAdmin()->post($this->fragmentManager->route('fragment-status', $this->fragment), [
            'online_status' => false,
        ]);

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);
        $this->assertFalse($fragments->first()->fragmentModel()->isOnline());
    }

    /** @test */
    public function it_can_be_put_online()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-status', $this->fragment), [
            'online_status' => true,
        ]);

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);
        $this->assertTrue($fragments->first()->fragmentModel()->isOnline());
    }

    /** @test */
    public function it_only_renders_children_that_are_online()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-store', $this->owner, $this->fragment), [
            'custom' => 'xx',
        ]);

        $this->asAdmin()->post($this->fragmentManager->route('fragment-store', $this->owner, $this->fragment), [
            'custom' => 'xx',
        ]);

        // First one will be offline
        $this->asAdmin()->post($this->fragmentManager->route('fragment-status', $this->fragment), [
            'online_status' => false,
        ]);

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner);
        $this->assertCount(3, $fragments);

        $this->assertEquals('quote-contentquote-content', app(FragmentsRenderer::class)->render($this->owner, []));
    }
}
