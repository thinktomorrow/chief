<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions\SelectOptions;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelResourceStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

final class NestableSelectOptionsTest extends ChiefTestCase
{
    use NestableTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->resource(NestableModelResourceStub::class);
        NestableModelResourceStub::migrateUp();
    }

    public function test_it_can_get_empty_select_options()
    {
        $options = app(SelectOptions::class)->getParentOptions(
            NestableModelStub::create(['id' => 'xxx'])
        );

        $this->assertCount(0, $options);
    }

    public function test_it_can_create_nestable_model()
    {
        $this->defaultNestables(true);

        $options = app(SelectOptions::class)->getParentOptions(
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
