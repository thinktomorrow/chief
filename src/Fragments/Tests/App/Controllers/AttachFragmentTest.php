<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class AttachFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private Fragment $fragment;

    private ContextModel $context;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->context = FragmentTestAssist::findOrCreateContext($this->owner);
        $this->fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $this->context->id);
    }

    public function test_a_context_can_attach_an_fragment()
    {
        $context = FragmentTestAssist::createContext($this->owner);

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $this->fragment->fragmentModel()->id]))
            ->assertStatus(201);

        FragmentTestAssist::assertFragmentCount($this->context->id, 1);
        FragmentTestAssist::assertFragmentCount($context->id, 1);
        $this->assertDatabaseCount('context_fragments', 1);
    }

    public function test_a_context_can_attach_an_fragment_with_a_given_order()
    {
        $context = FragmentTestAssist::createContext($this->owner);
        $otherFragmentId = FragmentTestAssist::createFragment(Quote::class)->getFragmentId();

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $this->fragment->fragmentModel()->id]).'?order=0')
            ->assertStatus(201);
        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $otherFragmentId]).'?order=0')
            ->assertStatus(201);

        $fragments = app(FragmentRepository::class)->getByContext($context->id);
        $this->assertCount(2, $fragments);

        $this->assertEquals(0, $fragments[0]->fragmentModel()->pivot->order);
        $this->assertEquals(1, $fragments[1]->fragmentModel()->pivot->order);
    }

    public function test_a_fragment_can_attach_a_nested_fragment()
    {
        $ownerFragmentId = app(CreateFragment::class)->handle(Quote::resourceKey(), []);
        $context = FragmentTestAssist::createContext($ownerFragment = FragmentModel::find($ownerFragmentId));

        $otherFragmentId = app(CreateFragment::class)->handle(Quote::resourceKey(), []);

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $otherFragmentId]))
            ->assertStatus(201);

        FragmentTestAssist::assertFragmentCount($context->id, 1);
    }

    //    public function test_it_can_check_if_a_model_allows_for_adding_a_fragment()
    //    {
    //        $this->assertFalse($this->manager($this->owner)->can('chief::fragments.attach'));
    //        $this->assertTrue($this->manager($this->fragment)->can('chief::fragments.attach'));
    //    }

    public function test_adding_a_fragment_multiple_times_only_adds_it_once()
    {
        $context = FragmentTestAssist::createContext($this->owner);

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $this->fragment->fragmentModel()->id]).'?order=0')
            ->assertStatus(201);
        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$context->id, $this->fragment->fragmentModel()->id]).'?order=0')
            ->assertStatus(400);

        FragmentTestAssist::assertFragmentCount($context->id, 1);
    }
}
