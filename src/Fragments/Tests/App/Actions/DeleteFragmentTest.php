<?php

namespace Tests\App\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Thinktomorrow\Chief\Fragments\Actions\DeleteFragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DeleteFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_delete_fragment()
    {
        [, $fragment] = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class);

        $this->assertDatabaseCount(FragmentModel::class, 1);

        app(DeleteFragment::class)->handle($fragment->getFragmentId());

        $this->assertEquals(0, FragmentModel::count());
    }

    public function test_it_deletes_fragment_from_all_contexts()
    {
        [$context, $fragment] = FragmentTestAssist::createContextAndAttachFragment($this->owner, SnippetStub::class);
        $context2 = FragmentTestAssist::createContext($this->owner);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertDatabaseCount(FragmentModel::class, 1);
        FragmentTestAssist::assertFragmentCount($context->id, 1);
        FragmentTestAssist::assertFragmentCount($context2->id,  1);

        app(DeleteFragment::class)->handle($fragment->getFragmentId());

        $this->assertEquals(0, FragmentModel::count());

        FragmentTestAssist::assertFragmentCount($context->id, 0);
        FragmentTestAssist::assertFragmentCount($context2->id, 0);
    }

    public function test_it_errors_when_deleting_fragment_that_doesnt_exist()
    {
        $this->expectException(ModelNotFoundException::class);

        app(DeleteFragment::class)->handle('xxx');
    }

}
