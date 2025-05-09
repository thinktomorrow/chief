<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachRootFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class AttachFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_attach_fragment_to_context()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $this->assertCount(0, $context->fragments()->get());

        $fragmentId = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, 1)->getFragmentId();

        $this->assertCount(1, $context->fragments()->get());
        $this->assertEquals($fragmentId, $context->fragments()->first()->id);
    }

    public function test_it_can_attach_fragment_to_multiple_contexts()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner, ['nl']);
        $context2 = FragmentTestHelpers::createContext($this->owner);
        $this->assertCount(0, $context->fragments()->get());

        $fragmentId = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, 1)->getFragmentId();
        app(AttachRootFragment::class)->handle($context2->id, $fragmentId, 0);

        $this->assertCount(1, $context->fragments()->get());
        $this->assertCount(1, $context2->fragments()->get());
        $this->assertEquals($fragmentId, $context->fragments()->first()->id);
        $this->assertEquals($fragmentId, $context2->fragments()->first()->id);
    }

    public function test_it_cannot_attach_same_fragment_to_same_context()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner, ['nl']);

        $this->assertCount(0, $context->fragments()->get());

        $fragmentId = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, 1)->getFragmentId();

        $this->expectException(FragmentAlreadyAdded::class);
        app(AttachRootFragment::class)->handle($context->id, $fragmentId, 2);
    }

    public function test_attaching_a_fragment_emits_event()
    {
        Event::fake();

        $context = FragmentTestHelpers::findOrCreateContext($this->owner);

        FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        Event::assertDispatched(FragmentAttached::class);
    }

    public function test_a_context_can_attach_an_fragment_with_a_given_order()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragmentId = FragmentTestHelpers::createFragment(SnippetStub::class)->getFragmentId();
        $fragmentId2 = FragmentTestHelpers::createFragment(SnippetStub::class)->getFragmentId();

        app(AttachRootFragment::class)->handle($context->id, $fragmentId, 0);
        app(AttachRootFragment::class)->handle($context->id, $fragmentId2, 0);

        $fragments = app(FragmentRepository::class)->getByContext($context->id);

        $this->assertEquals(0, $fragments[0]->getFragmentModel()->pivot->order);
        $this->assertEquals(1, $fragments[1]->getFragmentModel()->pivot->order);
    }

    public function test_attaching_fragment_on_contexts_owned_by_same_owner_is_also_considered_shared()
    {
        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class);

        $context2 = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertEquals(1, FragmentModel::count());
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());
    }
}
