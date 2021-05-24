<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class DetachSharedFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private ArticlePage $owner2;
    private Quote $fragment;
    private Manager $fragmentManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->owner2 = ArticlePage::create();
        $this->fragment = $this->setupAndCreateQuote($this->owner);
        $this->addFragment($this->fragment, $this->owner2);

        $this->fragmentManager = $this->manager($this->fragment);
    }

    /** @test */
    public function unsharing_a_fragment()
    {
        $this->disableExceptionHandling();
        // Assert it is a shared fragment
        $this->assertEquals($this->firstFragment($this->owner)->fragmentModel()->id, $this->firstFragment($this->owner2)->fragmentModel()->id);

        $this->asAdmin()->post($this->fragmentManager->route('fragment-detach-shared', $this->owner2, $this->fragment));

        $this->assertFragmentCount($this->owner2, 1);

        // Assert no longer the same shared fragment, but a separate one
        $detachedFragment = $this->firstFragment($this->owner2);

        $this->assertFalse($detachedFragment->fragmentModel()->isShared());
        $this->assertNotEquals($this->firstFragment($this->owner)->fragmentModel()->id, $detachedFragment->fragmentModel()->id);
        $this->assertNotEquals($this->firstFragment($this->owner)->fragmentModel()->model_reference, $detachedFragment->fragmentModel()->model_reference);
    }

    /** @test */
    public function a_detached_fragment_is_no_langer_considered_shared_when_its_only_used_by_one_model()
    {
        $this->asAdmin()->post($this->fragmentManager->route('fragment-detach-shared', $this->owner2, $this->fragment));

        $this->assertFalse($this->firstFragment($this->owner)->fragmentModel()->isShared());
    }
}
