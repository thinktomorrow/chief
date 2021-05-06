<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class AddFragmentTest extends ChiefTestCase
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
    public function a_page_can_add_an_existing_fragment()
    {
        $owner2 = ArticlePage::create();

        $this->asAdmin()->post($this->manager($this->fragment)->route('fragment-add', $owner2, $this->fragment));

        $this->assertFragmentCount($owner2, 1);
    }

    /** @test */
    public function a_nested_fragment_can_add_an_existing_fragment()
    {
        $this->disableExceptionHandling();

        $fragment = $this->addAsFragment(ArticlePage::create(), $this->owner);

        $this->asAdmin()->post($this->manager($fragment)->route('fragment-add', $this->fragment, $fragment))->assertStatus(201);

        $this->assertFragmentCount($this->fragment->fragmentModel(), 1);
    }

    /** @test */
    public function it_can_check_if_a_model_allows_for_adding_a_fragment()
    {
        $this->assertTrue($this->manager($this->owner)->can('fragment-add'));
        $this->assertTrue($this->manager($this->fragment)->can('fragment-add'));
    }

    /** @test */
    public function adding_a_fragment_multiple_times_only_adds_it_once()
    {
        $owner2 = ArticlePage::create();

        $this->asAdmin()->post($this->manager($this->fragment)->route('fragment-add', $owner2, $this->fragment));

        $this->asAdmin()->post($this->manager($this->fragment)->route('fragment-add', $owner2, $this->fragment));

        $this->assertFragmentCount($owner2, 1);
    }
}
