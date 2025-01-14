<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelResourceStub;

class NestableModelRenderTest extends ChiefTestCase
{
    use NestableTestHelpers;

    public function setUp(): void
    {
        parent::setUp();

        $this->app['view']->addNamespace('test-views', __DIR__ . '/../../../Shared/stubs/views');

        chiefRegister()->resource(NestableModelResourceStub::class);
        NestableModelResourceStub::migrateUp();
        $this->defaultNestables();
    }

    public function test_it_can_render_nestable()
    {
        $node = $this->findNode('third');

        $this->assertStringContainsString(
            '<h1>' . $node->title . '</h1>',
            $node->response()->getOriginalContent(),
        );
    }

    public function test_it_can_render_nestable_root()
    {
        $node = $this->findNode('first');

        $this->assertStringContainsString(
            '<h1>' . $node->title . '</h1>',
            $node->response()->getOriginalContent(),
        );
    }
}
