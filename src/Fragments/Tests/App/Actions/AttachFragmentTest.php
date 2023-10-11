<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
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
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
        $this->assertCount(0, $context->fragments()->get());

        // Create Fragment and attach
        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['foo' => 'bar']);
        app(AttachFragment::class)->handle($context->id, $fragmentId, 1);

        $this->assertCount(1, $context->fragments()->get());
        $this->assertEquals($fragmentId, $context->fragments()->first()->id);
    }

    public function test_it_can_attach_fragment_to_multiple_contexts()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
        $context2 = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
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
}
