<?php

namespace Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\DeleteContext;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DeleteContextTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private $context;
    private $fragment;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->context = FragmentTestAssist::findOrCreateContext($this->owner);
        $this->fragment = FragmentTestAssist::createAndAttachFragment(SnippetStub::class, $this->context->id);
    }

    public function test_context_can_be_deleted()
    {
        $this->assertEquals(1, ContextModel::count());

        app(DeleteContext::class)->handle($this->context->id);

        $this->assertEquals(0, ContextModel::count());
    }

    public function test_fragment_is_deleted_when_only_used_in_this_context()
    {
        $this->assertEquals(1, FragmentModel::count());

        app(DeleteContext::class)->handle($this->context->id);

        $this->assertEquals(0, FragmentModel::count());
    }

    public function test_fragment_is_not_deleted_when_used_in_more_than_one_context()
    {
        $otherContext = FragmentTestAssist::createContext($this->owner);
        FragmentTestAssist::attachFragment($otherContext->id, $this->fragment->getFragmentId());

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(1, FragmentModel::count());

        app(DeleteContext::class)->handle($this->context->id);

        $this->assertEquals(1, ContextModel::count());
        $this->assertEquals(1, FragmentModel::count());
    }
}
