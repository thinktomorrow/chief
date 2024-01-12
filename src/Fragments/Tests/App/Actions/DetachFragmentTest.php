<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DetachFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_detach_fragment_from_context()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'fr']);
        $fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        $context2 = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'en']);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1);

        FragmentTestAssist::assertFragmentCount($this->owner, 'fr', 1);
        FragmentTestAssist::assertFragmentCount($this->owner, 'en', 1);
        $this->assertNotNull(FragmentModel::find($fragment->getFragmentId()));

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($this->owner, 'fr', 0);
        FragmentTestAssist::assertFragmentCount($this->owner, 'en', 1);
        $this->assertNotNull(FragmentModel::find($fragment->getFragmentId()));
    }

    public function test_it_deletes_fragment_when_after_detach_fragment_is_no_longer_used()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'fr']);
        $fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        FragmentTestAssist::assertFragmentCount($this->owner, 'fr', 1);
        $this->assertNotNull(FragmentModel::find($fragment->getFragmentId()));

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($this->owner, 'fr', 0);
        $this->assertNull(FragmentModel::find($fragment->getFragmentId()));
    }

    public function test_when_detaching_shared_fragment_it_is_no_longer_considered_shared_when_used_by_one_context()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'fr']);
        $fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::resourceKey(), $context->id);

        $owner2 = ArticlePage::create();
        $context2 = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'fr']);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1);

        FragmentTestAssist::assertFragmentCount($this->owner, 'fr', 1);
        FragmentTestAssist::assertFragmentCount($owner2, 'fr', 1);
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($this->owner, 'fr', 0);
        FragmentTestAssist::assertFragmentCount($owner2, 'fr', 1);
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
    }

}
