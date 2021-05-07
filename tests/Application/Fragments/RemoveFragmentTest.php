<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class RemoveFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $fragment;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->owner);
    }

    /** @test */
    public function a_page_can_remove_a_fragment()
    {
        $this->assertFragmentCount($this->owner, 1);

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->owner, $this->fragment));

        $this->assertFragmentCount($this->owner, 0);
    }

    /** @test */
    public function a_fragment_can_remove_a_nested_fragment()
    {
        $fragment = $this->addAsFragment(ArticlePage::create(), $this->fragment->fragmentModel());

        $this->asAdmin()->delete($this->manager($this->fragment)->route('fragment-delete', $this->fragment, $fragment))->assertStatus(200);

        $this->assertFragmentCount($this->fragment->fragmentModel(), 0);
    }

    /** @test */
    public function it_can_check_if_a_model_allows_for_removing_a_fragment()
    {
        $this->assertTrue($this->manager($this->owner)->can('fragment-delete'));
        $this->assertTrue($this->manager($this->fragment)->can('fragment-delete'));
    }

    /** @test */
    public function removing_a_fragment_multiple_times_only_removes_it_once()
    {
        $fragment = $this->addAsFragment(ArticlePage::create(), $this->owner);
        $this->assertFragmentCount($this->owner, 2);

        $this->asAdmin()->delete($this->manager($this->owner)->route('fragment-delete', $this->owner, $fragment));

        $this->asAdmin()->delete($this->manager($this->owner)->route('fragment-delete', $this->owner, $fragment));

        $this->assertFragmentCount($this->owner, 1);
    }
}
