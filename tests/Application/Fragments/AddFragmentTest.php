<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\Events\FragmentAdded;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class AddFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private Quote $fragment;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->owner);
    }

    public function test_a_page_can_add_an_existing_fragment()
    {
        $owner2 = ArticlePage::create();

        $this->asAdmin()->post($this->manager($this->fragment)->route('fragment-add', $owner2, $this->fragment));

        $this->assertFragmentCount($owner2, 1);
    }

    public function test_adding_a_fragment_emits_event()
    {
        Event::fake();

        $this->asAdmin()->post($this->manager($this->fragment)->route('fragment-add', ArticlePage::create(), $this->fragment));

        Event::assertDispatched(FragmentAdded::class);
    }

    public function test_a_page_can_add_an_existing_fragment_with_a_given_order()
    {
        $owner2 = ArticlePage::create();
        $snippet = $this->createAsFragment(new SnippetStub, $this->owner);

        $this->asAdmin()->post($this->manager($this->fragment)->route('fragment-add', $owner2, $this->fragment).'?order=0');
        $this->asAdmin()->post($this->manager($this->fragment)->route('fragment-add', $owner2, $snippet).'?order=0');

        $this->assertFragmentCount($owner2, 2);
        $this->assertEquals(SnippetStub::class, get_class($this->firstFragment($owner2)));
    }

    public function test_a_nested_fragment_can_add_an_existing_fragment()
    {
        $fragment = $this->createAsFragment(Quote::create(), $this->owner);

        $this->asAdmin()->post($this->manager($fragment)->route('fragment-add', $this->fragment, $fragment))->assertStatus(201);

        $this->assertFragmentCount($this->fragment->fragmentModel(), 1);
    }

    public function test_it_can_check_if_a_model_allows_for_adding_a_fragment()
    {
        $this->assertFalse($this->manager($this->owner)->can('fragment-add'));
        $this->assertTrue($this->manager($this->fragment)->can('fragment-add'));
    }

    public function test_adding_a_fragment_multiple_times_only_adds_it_once()
    {
        $owner2 = ArticlePage::create();

        $this->asAdmin()->post($this->manager($this->fragment)->route('fragment-add', $owner2, $this->fragment));
        $this->asAdmin()->post($this->manager($this->fragment)->route('fragment-add', $owner2, $this->fragment));

        $this->assertFragmentCount($owner2, 1);
    }
}
