<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\ContextActions;

use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\DeleteContext;
use Thinktomorrow\Chief\Fragments\Exceptions\SafeContextDeleteException;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DeleteContextTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private $context;

    private $fragment;

    private ContextApplication $contextApplication;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $this->fragment = FragmentTestHelpers::createAndAttachFragment(SnippetStub::class, $this->context->id);

        $this->contextApplication = app(ContextApplication::class);
    }

    public function test_context_can_be_deleted()
    {
        $this->assertEquals(1, ContextModel::count());

        $this->contextApplication->delete(new DeleteContext($this->context->id));

        $this->assertEquals(0, ContextModel::count());
    }

    public function test_fragment_is_deleted_when_only_used_in_this_context()
    {
        $this->assertEquals(1, FragmentModel::count());

        $this->contextApplication->delete(new DeleteContext($this->context->id));

        $this->assertEquals(0, FragmentModel::count());
    }

    public function test_fragment_is_not_deleted_when_used_in_more_than_one_context_of_the_same_owner()
    {
        $otherContext = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::attachFragment($otherContext->id, $this->fragment->getFragmentId());

        $this->assertEquals(2, ContextModel::count());
        $this->assertEquals(1, FragmentModel::count());

        $this->contextApplication->delete(new DeleteContext($this->context->id));

        $this->assertEquals(1, ContextModel::count());
        $this->assertEquals(1, FragmentModel::count());
    }

    public function test_it_prevents_deleting_last_context_for_owner()
    {
        $owner = ArticlePage::create([]);
        $context = FragmentTestHelpers::createContext($owner);

        $this->expectException(SafeContextDeleteException::class);

        app(ContextApplication::class)->safeDelete(new DeleteContext($context->id));
    }

    public function test_it_prevents_deleting_context_with_active_site()
    {
        $owner = ArticlePage::create([]);
        $context = FragmentTestHelpers::createContext($owner, [], ['nl']);

        // Add second context to allow deletion
        FragmentTestHelpers::createContext($owner, [], []);

        $this->expectException(SafeContextDeleteException::class);

        app(ContextApplication::class)->safeDelete(new DeleteContext($context->id));
    }
}
