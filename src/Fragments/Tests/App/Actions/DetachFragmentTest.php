<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
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
        [$context, $fragment] = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class);

        $context2 = FragmentTestAssist::createContext($this->owner);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($context->id,  1);
        FragmentTestAssist::assertFragmentCount($context2->id, 1);
        $this->assertNotNull(FragmentModel::find($fragment->getFragmentId()));

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($context->id,  0);
        FragmentTestAssist::assertFragmentCount($context2->id, 1);
        $this->assertNotNull(FragmentModel::find($fragment->getFragmentId()));
    }

    public function test_it_deletes_fragment_when_after_detachment_the_fragment_no_longer_has_an_owner()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $context->id);

        FragmentTestAssist::assertFragmentCount($context->id,  1);
        $this->assertNotNull(FragmentModel::find($fragment->getFragmentId()));

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($context->id,  0);
        $this->assertNull(FragmentModel::find($fragment->getFragmentId()));
    }

    public function test_when_detaching_shared_fragment_it_is_no_longer_considered_shared_when_used_by_one_context()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $context->id);

        $owner2 = ArticlePage::create();
        $context2 = FragmentTestAssist::createContext($owner2);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($context->id, 1);
        FragmentTestAssist::assertFragmentCount($context2->id, 1);
        $this->assertTrue(FragmentModel::find($fragment->getFragmentId())->isShared());

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestAssist::assertFragmentCount($context->id,  0);
        FragmentTestAssist::assertFragmentCount($context2->id, 1);
        $this->assertFalse(FragmentModel::find($fragment->getFragmentId())->isShared());
    }

}
