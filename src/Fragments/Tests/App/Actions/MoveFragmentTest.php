<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use InvalidArgumentException;
use Thinktomorrow\Chief\Fragments\App\Actions\MoveFragment;
use Thinktomorrow\Chief\Fragments\Events\FragmentMoved;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Fragments\Tests\Stubs\MoveContainerStub;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class MoveFragmentTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        chiefRegister()->fragment(MoveContainerStub::class);
    }

    public function test_it_moves_between_parents_and_root_and_reindexes_both_locations(): void
    {
        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $first = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id);
        $second = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id);
        $child = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, $first->getFragmentId());
        $remaining = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, $first->getFragmentId(), 1);
        $existing = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, $second->getFragmentId());

        app(MoveFragment::class)->handle($context->id, $child->getFragmentId(), $second->getFragmentId(), 0);

        $this->assertPosition($context->id, $child->getFragmentId(), $second->getFragmentId(), 0);
        $this->assertPosition($context->id, $existing->getFragmentId(), $second->getFragmentId(), 1);
        $this->assertPosition($context->id, $remaining->getFragmentId(), $first->getFragmentId(), 0);

        app(MoveFragment::class)->handle($context->id, $child->getFragmentId(), null);

        $this->assertPosition($context->id, $child->getFragmentId(), null, 2);
        $this->assertPosition($context->id, $existing->getFragmentId(), $second->getFragmentId(), 0);
    }

    public function test_it_reorders_within_the_same_parent_only_when_an_order_is_given(): void
    {
        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $first = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);
        $second = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, null, 1);

        Event::fake(FragmentMoved::class);

        $this->assertFalse(app(MoveFragment::class)->handle($context->id, $first->getFragmentId(), null));
        Event::assertNotDispatched(FragmentMoved::class);

        $this->assertTrue(app(MoveFragment::class)->handle($context->id, $first->getFragmentId(), null, 1));
        Event::assertDispatched(FragmentMoved::class);
        $this->assertPosition($context->id, $second->getFragmentId(), null, 0);
        $this->assertPosition($context->id, $first->getFragmentId(), null, 1);
    }

    public function test_it_rejects_a_descendant_without_changing_the_tree(): void
    {
        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $parent = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id);
        $child = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id, $parent->getFragmentId());
        $grandchild = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id, $child->getFragmentId());

        try {
            app(MoveFragment::class)->handle($context->id, $parent->getFragmentId(), $grandchild->getFragmentId());
            $this->fail('A descendant must not be a valid destination.');
        } catch (InvalidArgumentException $e) {
            $this->assertSame('A fragment cannot be moved below itself or one of its descendants.', $e->getMessage());
        }

        $this->assertPosition($context->id, $parent->getFragmentId(), null, 0);
        $this->assertPosition($context->id, $child->getFragmentId(), $parent->getFragmentId(), 0);
        $this->assertPosition($context->id, $grandchild->getFragmentId(), $child->getFragmentId(), 0);
    }

    public function test_it_rejects_a_parent_that_does_not_allow_the_fragment_type(): void
    {
        $context = FragmentTestHelpers::createContext($this->setUpAndCreateArticle());
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);
        $parent = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('This fragment type is not allowed at the selected location.');

        app(MoveFragment::class)->handle($context->id, $fragment->getFragmentId(), $parent->getFragmentId());
    }

    public function test_moving_a_shared_container_keeps_its_nested_shared_fragments_in_both_contexts(): void
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner);
        $otherContext = FragmentTestHelpers::createContext(ArticlePage::create());

        $destination = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id);
        $container = FragmentTestHelpers::createAndAttachFragment(MoveContainerStub::class, $context->id, null, 1);
        $child = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id, $container->getFragmentId());

        FragmentTestHelpers::attachFragment($otherContext->id, $destination->getFragmentId(), sourceContextId: $context->id);
        FragmentTestHelpers::attachFragment($otherContext->id, $container->getFragmentId(), sourceContextId: $context->id);

        app(MoveFragment::class)->handle($context->id, $container->getFragmentId(), $destination->getFragmentId());

        $this->assertPosition($context->id, $container->getFragmentId(), $destination->getFragmentId(), 0);
        $this->assertPosition($context->id, $child->getFragmentId(), $container->getFragmentId(), 0);
        $this->assertPosition($otherContext->id, $destination->getFragmentId(), null, 1);
        $this->assertPosition($otherContext->id, $container->getFragmentId(), null, 0);
        $this->assertPosition($otherContext->id, $child->getFragmentId(), $container->getFragmentId(), 0);
        $this->assertTrue(FragmentModel::find($container->getFragmentId())->isShared());
        $this->assertTrue(FragmentModel::find($child->getFragmentId())->isShared());
        $this->assertTrue(FragmentModel::find($destination->getFragmentId())->isShared());

        app(MoveFragment::class)->handle($context->id, $child->getFragmentId(), $destination->getFragmentId());

        $this->assertPosition($context->id, $child->getFragmentId(), $destination->getFragmentId(), 1);
        $this->assertPosition($otherContext->id, $child->getFragmentId(), $container->getFragmentId(), 0);
        $this->assertTrue(FragmentModel::find($child->getFragmentId())->isShared());
    }

    private function assertPosition(string $contextId, string $fragmentId, ?string $parentId, int $order): void
    {
        $row = DB::table('context_fragment_tree')
            ->where('context_id', $contextId)
            ->where('child_id', $fragmentId)
            ->first();

        $this->assertNotNull($row);
        $this->assertSame($parentId, $row->parent_id);
        $this->assertSame($order, $row->order);
    }
}
