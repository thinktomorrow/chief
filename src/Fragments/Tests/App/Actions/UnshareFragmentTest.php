<?php

namespace Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\UnshareFragment;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class UnshareFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private ArticlePage $owner2;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->owner2 = ArticlePage::create();
    }

    public function test_unshare_fragment_duplicates_fragment()
    {
        [$context, $fragment] = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class, 'fr');

        $context2 = FragmentTestAssist::findOrCreateContext($this->owner2, 'en');
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertEquals(1, FragmentModel::count());
        FragmentTestAssist::assertFragmentCount($this->owner, 'fr', 1);
        FragmentTestAssist::assertFragmentCount($this->owner2, 'en', 1);
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());

        app(UnshareFragment::class)->handle($context->id, $fragment->getFragmentId());

        $this->assertEquals(2, FragmentModel::count());
        $fragment1 = FragmentTestAssist::firstFragment($this->owner, 'fr');
        $fragment2 = FragmentTestAssist::firstFragment($this->owner2, 'en');
        FragmentTestAssist::assertFragmentCount($this->owner, 'fr', 1);
        FragmentTestAssist::assertFragmentCount($this->owner2, 'en', 1);
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
        $this->assertFalse($fragment1->isShared());
        $this->assertFalse($fragment2->isShared());
        $this->assertEquals($fragment2->values, $fragment1->values);
    }

    public function test_when_it_belongs_to_more_then_two_contexts_it_stays_shared_when_unshared()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner, 'fr');
        $fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $context->id);

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

    public function test_when_it_belongs_to_two_contexts_it_is_no_longer_shared_when_unshared()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner, 'fr');
        $fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $context->id);

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
