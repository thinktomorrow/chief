<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestableRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\NestableArticlePage;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

final class NestableRepositoryTest extends ChiefTestCase
{
    use NestableTestHelpers;

    private NestableRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class]);
    }

    public function test_results_can_be_empty()
    {
        NestableModelStub::migrateUp();

        $this->assertNull($this->repository->findNestableById('xxx'));
        $this->assertInstanceOf(NestedTree::class, $this->repository->getTree());
        $this->assertCount(0, $this->repository->getTree());
    }

    public function test_results_can_be_tree()
    {
        NestableModelStub::migrateUp();

        $this->defaultNestables();

        $this->assertCount(2, $this->repository->getTree());
        $this->assertEquals(5, $this->repository->getTree()->total());
        $this->assertCount(2, $this->repository->getTree()[0]->getChildNodes());
        $this->assertCount(1, $this->repository->getTree()[0]->getChildNodes()[1]->getChildNodes());
        $this->assertCount(0, $this->repository->getTree()[1]->getChildNodes());
    }

    public function test_it_can_give_response()
    {
        NestableArticlePage::migrateUp();

        $model = NestableArticlePage::create(['title' => 'foobar']);

        $this->assertNotNull($model->response());
    }
}
