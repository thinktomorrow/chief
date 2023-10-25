<?php

namespace Tests\App\Controllers;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Resource\Events\FragmentAdded;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class AttachFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $fragment;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->owner);
    }

    public function test_admin_can_view_the_fragment_edit_form()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');

        $this->asAdmin()
            ->get(route('chief::fragments.edit', [$context->id, $this->fragment->fragmentModel()->id]))
            ->assertStatus(200);
    }

    public function test_a_context_can_attach_an_fragment()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'other');

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $this->fragment->fragmentModel()->id]))
            ->assertStatus(201);

        $this->assertFragmentCount($this->owner, 'nl', 1);
        $this->assertFragmentCount($this->owner, 'other', 1);
        $this->assertDatabaseCount('context_fragments', 1);
    }

    public function test_adding_a_fragment_emits_event()
    {
        Event::fake();

        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'other');

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $this->fragment->fragmentModel()->id]))
            ->assertStatus(201);

        Event::assertDispatched(FragmentAdded::class);
    }

    public function test_a_context_can_attach_an_fragment_with_a_given_order()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'other');
        $otherFragmentId = app(CreateFragment::class)->handle(Quote::resourceKey(), []);

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $this->fragment->fragmentModel()->id]) . '?order=0')
            ->assertStatus(201);
        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $otherFragmentId]) . '?order=0')
            ->assertStatus(201);

        $fragments = app(FragmentRepository::class)->getByOwner($this->owner, 'other');

        $this->assertEquals(0, $fragments[0]->fragmentModel()->pivot_order);
        $this->assertEquals(1, $fragments[1]->fragmentModel()->pivot_order);
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
