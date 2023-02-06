<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use ReflectionMethod;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

class NestableModelRenderTest extends ChiefTestCase
{
    use NestableTestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['view']->addNamespace('test-views', __DIR__.'/../../../Shared/stubs/views');

        chiefRegister()->resource(NestableModelStub::class);
        NestableModelStub::migrateUp();
        $this->defaultNestables();
    }

    public function test_it_can_render_nestable()
    {
        $node = $this->findNode('third');

        $this->assertStringContainsString(
            '<h1>'.$node->getModel()->title.'</h1>',
            $node->getModel()->response()->getOriginalContent(),
        );
    }

    public function test_it_can_render_nestable_root()
    {
        $node = $this->findNode('first');

        $this->assertStringContainsString(
            '<h1>'.$node->getModel()->title.'</h1>',
            $node->getModel()->response()->getOriginalContent(),
        );
    }
}
