<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\UnshareFragment;
use Thinktomorrow\Chief\Fragments\Domain\Events\FragmentAttached;
use Thinktomorrow\Chief\Fragments\Domain\Exceptions\FragmentAlreadyAdded;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class AttachFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_attach_fragment_to_context()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $this->assertCount(0, $context->fragments()->get());

        // Create Fragment and attach
        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['foo' => 'bar']);
        app(AttachFragment::class)->handle($context->id, $fragmentId, 1);

        $this->assertCount(1, $context->fragments()->get());
        $this->assertEquals($fragmentId, $context->fragments()->first()->id);
    }

    public function test_it_can_attach_fragment_to_multiple_contexts()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $context2 = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'fr');
        $this->assertCount(0, $context->fragments()->get());

        // Create Fragment and attach
        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['foo' => 'bar']);
        app(AttachFragment::class)->handle($context->id, $fragmentId, 1);
        app(AttachFragment::class)->handle($context2->id, $fragmentId, 2);

        $this->assertCount(1, $context->fragments()->get());
        $this->assertCount(1, $context2->fragments()->get());
        $this->assertEquals($fragmentId, $context->fragments()->first()->id);
        $this->assertEquals($fragmentId, $context2->fragments()->first()->id);
    }

    public function test_it_cannot_attach_same_fragment_to_same_context()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $context2 = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');

        $this->assertCount(0, $context->fragments()->get());

        // Create Fragment and attach
        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['foo' => 'bar']);
        app(AttachFragment::class)->handle($context->id, $fragmentId, 1);

        $this->expectException(FragmentAlreadyAdded::class);
        app(AttachFragment::class)->handle($context2->id, $fragmentId, 2);
    }

    public function test_attaching_a_fragment_emits_event()
    {
        Event::fake();

        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'other');

        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['foo' => 'bar']);
        app(AttachFragment::class)->handle($context->id, $fragmentId, 2);

        Event::assertDispatched(FragmentAttached::class);
    }

    public function test_a_context_can_attach_an_fragment_with_a_given_order()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['foo' => 'bar']);
        $fragmentId2 = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['foo' => 'bar']);

        app(AttachFragment::class)->handle($context->id, $fragmentId, 0);
        app(AttachFragment::class)->handle($context->id, $fragmentId2, 0);

        $fragments = app(FragmentRepository::class)->getByContext($context->id);

        $this->assertEquals(0, $fragments[0]->fragmentModel()->pivot->order);
        $this->assertEquals(1, $fragments[1]->fragmentModel()->pivot->order);
    }

    public function test_attaching_fragment_on_contexts_owned_by_same_owner_is_not_considered_shared()
    {
        [$context, $fragment] = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class, 'fr');

        $context2 = FragmentTestAssist::findOrCreateContext($this->owner, 'en');
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertEquals(1, FragmentModel::count());
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
    }
}
