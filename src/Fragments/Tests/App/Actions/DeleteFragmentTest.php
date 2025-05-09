<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Thinktomorrow\Chief\Fragments\App\Actions\DeleteFragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class DeleteFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_it_can_delete_fragment()
    {
        [, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class);

        $this->assertDatabaseCount(FragmentModel::class, 1);

        app(DeleteFragment::class)->handle($fragment->getFragmentId());

        $this->assertEquals(0, FragmentModel::count());
    }

    public function test_it_deletes_fragment_from_all_contexts()
    {
        [$context, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->owner, SnippetStub::class);
        $context2 = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertDatabaseCount(FragmentModel::class, 1);
        FragmentTestHelpers::assertFragmentCount($context->id, 1);
        FragmentTestHelpers::assertFragmentCount($context2->id, 1);

        app(DeleteFragment::class)->handle($fragment->getFragmentId());

        $this->assertEquals(0, FragmentModel::count());

        FragmentTestHelpers::assertFragmentCount($context->id, 0);
        FragmentTestHelpers::assertFragmentCount($context2->id, 0);
    }

    public function test_it_errors_when_deleting_fragment_that_doesnt_exist()
    {
        $this->expectException(ModelNotFoundException::class);

        app(DeleteFragment::class)->handle('xxx');
    }
}
