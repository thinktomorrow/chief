<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use ReflectionMethod;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

class NestablePageRenderTest extends ChiefTestCase
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

    public function test_it_passes_node_to_view()
    {
        $node = $this->findNode('third');

        // Call private viewData()
        $reflectionMethod = new ReflectionMethod(NestableModelStub::class, 'viewData');
        $reflectionMethod->setAccessible(true);

        $viewData = $reflectionMethod->invoke($node->getModel());

        $this->assertEquals([
            'node' => $node,
            'model' => $node->getModel(),
        ], $viewData);
    }

    public function test_it_can_render_model_properties()
    {
        $node = $this->findNode('third');

        $this->assertStringContainsString(
            '<h1>'.$node->getLabel().'</h1>',
            $node->getModel()->response()->getOriginalContent(),
        );
    }
}
