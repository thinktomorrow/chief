<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
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
        $this->context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $this->fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $this->context->id);
    }

    public function test_a_context_can_attach_a_root_fragment()
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $this->asAdmin()
            ->post(route('chief::fragments.attach-root', [$context->id, $this->fragment->getFragmentModel()->id]))
            ->assertStatus(201);

        FragmentTestHelpers::assertFragmentCount($this->context->id, 1);
        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        $this->assertDatabaseCount('context_fragments', 1);
    }

    public function test_a_context_can_attach_an_fragment_with_a_given_order()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $otherFragmentId = FragmentTestHelpers::createFragment(Quote::class)->getFragmentId();

        $this->asAdmin()
            ->post(route('chief::fragments.attach-root', [$context->id, $this->fragment->getFragmentModel()->id]).'?order=0')
            ->assertStatus(201);
        $this->asAdmin()
            ->post(route('chief::fragments.attach-root', [$context->id, $otherFragmentId]).'?order=0')
            ->assertStatus(201);

        $fragments = app(FragmentRepository::class)->getByContext($context->id);
        $this->assertCount(2, $fragments);

        $this->assertEquals(0, $fragments[0]->fragmentModel()->pivot->order);
        $this->assertEquals(1, $fragments[1]->fragmentModel()->pivot->order);
    }

    public function test_a_fragment_can_attach_a_nested_fragment()
    {
        $parentFragmentId = $this->fragment->getFragmentId();
        $otherFragmentId = app(CreateFragment::class)->handle(Quote::resourceKey(), []);

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$this->context->id, $otherFragmentId, $parentFragmentId]))
            ->assertStatus(201);

        FragmentTestHelpers::assertFragmentCount($this->context->id, 2);
        FragmentTestHelpers::assertFragmentCount($this->context->id, 1, $otherFragmentId, $parentFragmentId);
    }

    public function test_it_cannot_add_a_root_fragment_twice()
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $this->asAdmin()
            ->post(route('chief::fragments.attach-root', [$context->id, $this->fragment->getFragmentModel()->id]).'?order=0')
            ->assertStatus(201);
        $this->asAdmin()
            ->post(route('chief::fragments.attach-root', [$context->id, $this->fragment->getFragmentModel()->id]).'?order=0')
            ->assertStatus(400);

        FragmentTestHelpers::assertFragmentCount($context->id, 1);
    }

    public function test_it_cannot_add_a_fragment_twice()
    {
        $parentFragmentId = $this->fragment->getFragmentId();
        $parentFragmentId2 = FragmentTestHelpers::createAndAttachFragment(Quote::class, $this->context->id)->getFragmentId();
        $otherFragmentId = app(CreateFragment::class)->handle(Quote::resourceKey(), []);

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$this->context->id, $otherFragmentId, $parentFragmentId]))
            ->assertStatus(201);

        $this->asAdmin()
            ->post(route('chief::fragments.attach', [$this->context->id, $otherFragmentId, $parentFragmentId2]))
            ->assertStatus(400);

        FragmentTestHelpers::assertFragmentCount($this->context->id, 3);
        FragmentTestHelpers::assertFragmentCount($this->context->id, 1, $parentFragmentId);
        FragmentTestHelpers::assertFragmentCount($this->context->id, 1, $parentFragmentId2);
        FragmentTestHelpers::assertFragmentCount($this->context->id, 1, $otherFragmentId, $parentFragmentId);
        FragmentTestHelpers::assertFragmentCount($this->context->id, 0, $otherFragmentId, $parentFragmentId2);
    }
}
