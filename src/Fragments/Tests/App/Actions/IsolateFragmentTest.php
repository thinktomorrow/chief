<?php

namespace Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\IsolateFragment;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Domain\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class IsolateFragmentTest extends ChiefTestCase
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

    public function test_isolate_fragment_duplicates_fragment()
    {
        [$context, $fragment] = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class);

        $context2 = FragmentTestAssist::createContext($this->owner2);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertEquals(1, FragmentModel::count());
        FragmentTestAssist::assertFragmentCount($context->id, 1);
        FragmentTestAssist::assertFragmentCount($context2->id, 1);
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());

        app(IsolateFragment::class)->handle($context->id, $fragment->getFragmentId());

        $this->assertEquals(2, FragmentModel::count());
        $fragment1 = FragmentTestAssist::firstFragment($context->id);
        $fragment2 = FragmentTestAssist::firstFragment($context2->id);
        FragmentTestAssist::assertFragmentCount($context->id,  1);
        FragmentTestAssist::assertFragmentCount($context2->id,  1);
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
        $this->assertFalse($fragment1->fragmentModel()->isShared());
        $this->assertFalse($fragment2->fragmentModel()->isShared());
        $this->assertEquals($fragment2->values, $fragment1->values);
    }

    public function test_when_it_belongs_to_more_then_two_contexts_it_stays_shared_when_isolated()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $context->id);

        $owner2 = ArticlePage::create();
        $context2 = FragmentTestAssist::createContext($owner2);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($context->id,  1);
        FragmentTestAssist::assertFragmentCount($context2->id, 1);
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($context->id,  0);
        FragmentTestAssist::assertFragmentCount($context2->id,  1);
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
    }

    public function test_when_it_belongs_to_two_contexts_it_is_no_longer_shared_when_isolated()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $context->id);

        $owner2 = ArticlePage::create();
        $context2 = FragmentTestAssist::createContext($owner2);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($context->id,1);
        FragmentTestAssist::assertFragmentCount($context2->id, 1);
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($context->id, 0);
        FragmentTestAssist::assertFragmentCount($context2->id, 1);
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
    }
}
