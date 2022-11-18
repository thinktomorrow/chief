<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageRepository;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\SelectOptions;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

final class NestableSelectOptionsTest extends ChiefTestCase
{
    use NestableTestHelpers;

    private NestablePageRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->resource(NestableModelStub::class);
        NestableModelStub::migrateUp();

        $this->repository = app()->makeWith(NestablePageRepository::class, ['modelClass' => NestableModelStub::class]);
    }

    public function test_it_can_get_empty_select_options()
    {
        $options = app(SelectOptions::class)->getParentOptions(
            $this->repository->getTree(),
            NestableModelStub::create(['id' => 'xxx'])
        );

        $this->assertCount(0, $options);
    }

    public function test_it_can_create_nestable_model()
    {
        $this->defaultNestables();

        $options = app(SelectOptions::class)->getParentOptions(
            $this->repository->getTree(),
            NestableModelStub::find('third')
        );

        // Fourth is a child of third so this should not be included in the options
        $this->assertCount(3, $options);
        $this->assertEquals([
            'first' => 'label first nl',
            'second' => 'label first nl: label second nl',
            'fifth' => 'label fifth nl',
        ], $options);
    }
}
