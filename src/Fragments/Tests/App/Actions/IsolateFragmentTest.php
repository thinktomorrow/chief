<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\IsolateFragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class IsolateFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private ArticlePage $owner2;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->owner2 = ArticlePage::create();
    }

    public function test_isolate_fragment_duplicates_fragment()
    {
        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class);

        $context2 = FragmentTestHelpers::createContext($this->owner2);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertEquals(1, FragmentModel::count());
        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());

        app(IsolateFragment::class)->handle($context->id, $fragment->getFragmentId());

        $this->assertEquals(2, FragmentModel::count());
        $fragment1 = FragmentTestHelpers::firstFragment($context->id);
        $fragment2 = FragmentTestHelpers::firstFragment($context2->id);
        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
        $this->assertFalse($fragment1->getFragmentModel()->isShared());
        $this->assertFalse($fragment2->fragmentModel()->isShared());
        $this->assertEquals($fragment2->fragmentModel()->values, $fragment1->fragmentModel()->values);
    }

    public function test_when_it_belongs_to_more_then_two_contexts_it_stays_shared_when_isolated()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        $owner2 = ArticlePage::create();
        $context2 = FragmentTestHelpers::createContext($owner2);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestHelpers::assertFragmentCount($context->id, 0);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
    }

    public function test_when_it_belongs_to_two_contexts_it_is_no_longer_shared_when_isolated()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        $owner2 = ArticlePage::create();
        $context2 = FragmentTestHelpers::createContext($owner2);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestHelpers::assertFragmentCount($context->id, 0);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
    }
}
