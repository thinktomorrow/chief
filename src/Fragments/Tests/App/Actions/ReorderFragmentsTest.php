<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Fragments\App\Actions\ReorderFragments;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Events\FragmentsReordered;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class ReorderFragmentsTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_reorder_fragments()
    {
        [$context, $snippet1] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, 1);
        [, $snippet2] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, 2);
        [, $snippet3] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, 3);

        app(ReorderFragments::class)->handle($context->id, [
            $snippet3->getFragmentId(),
            $snippet1->getFragmentId(),
            $snippet2->getFragmentId(),
        ]);

        $fragments = app(FragmentRepository::class)->getByContext($context->id);
        $this->assertCount(3, $fragments);

        $this->assertEquals($snippet3->getFragmentId(), $fragments[0]->getFragmentId());
        $this->assertEquals($snippet1->getFragmentId(), $fragments[1]->getFragmentId());
        $this->assertEquals($snippet2->getFragmentId(), $fragments[2]->getFragmentId());

        // Assert order is updated accordingly
        $this->assertEquals(0, $fragments[0]->getFragmentModel()->pivot->order);
        $this->assertEquals(1, $fragments[1]->getFragmentModel()->pivot->order);
        $this->assertEquals(2, $fragments[2]->fragmentModel()->pivot->order);
    }

    public function test_it_ignores_unknown_fragment_ids()
    {
        [$context, $snippet1] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, 1);

        app(ReorderFragments::class)->handle($context->id, [
            300,
            $snippet1->getFragmentId(),
            200,
        ]);

        $fragments = app(FragmentRepository::class)->getByContext($context->id);
        $this->assertCount(1, $fragments);

        $this->assertEquals($snippet1->getFragmentId(), $fragments[0]->getFragmentId());

        // sequence is still kept as given, even when other ids do not match
        $this->assertEquals(1, $fragments[0]->fragmentModel()->pivot->order);
    }

    public function test_it_ignores_reordering_on_empty_payload()
    {
        Event::fake();

        $context = FragmentTestHelpers::findOrCreateContext($this->owner);

        app(ReorderFragments::class)->handle($context->id, []);

        Event::assertNotDispatched(FragmentsReordered::class);
    }

    public function test_it_emits_event_after_reordering()
    {
        Event::fake();

        [$context, $snippet1] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class, 1);

        app(ReorderFragments::class)->handle($context->id, [
            $snippet1->getFragmentId(),
        ]);

        Event::assertDispatched(FragmentsReordered::class);
    }
}
