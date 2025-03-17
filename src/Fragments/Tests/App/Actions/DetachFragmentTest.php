<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\DetachFragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DetachFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_detach_fragment_from_context()
    {
        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class);

        $context2 = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);
        $this->assertNotNull(FragmentModel::find($fragment->getFragmentId()));

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestHelpers::assertFragmentCount($context->id, 0);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);
        $this->assertNotNull(FragmentModel::find($fragment->getFragmentId()));
    }

    public function test_it_deletes_fragment_when_after_detachment_the_fragment_no_longer_has_an_owner()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $context->id);

        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        $this->assertNotNull(FragmentModel::find($fragment->getFragmentId()));

        app(DetachFragment::class)->handle($context->id, $fragment->getFragmentId());

        FragmentTestHelpers::assertFragmentCount($context->id, 0);
        $this->assertNull(FragmentModel::find($fragment->getFragmentId()));
    }

    public function test_when_detaching_shared_fragment_it_is_no_longer_considered_shared_when_used_by_one_context()
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
