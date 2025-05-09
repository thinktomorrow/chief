<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelResourceStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

class NestableTest extends TestCase
{
    use NestableTestHelpers;

    public function test_it_can_create_nestable_model()
    {
        $node = new NestableModelStub;

        $this->assertInstanceOf(Nestable::class, $node);
        $this->assertInstanceOf(NestableModelStub::class, $node);
    }

    public function test_it_can_get_id_values()
    {
        $node = new NestableModelStub(['id' => '1', 'parent_id' => '5']);

        $this->assertEquals('1', $node->getNodeId());
        $this->assertEquals('5', $node->getParentNodeId());

        $node = new NestableModelStub(['id' => '1']);

        $this->assertEquals('1', $node->getNodeId());
        $this->assertNull($node->getParentNodeId());
    }

    public function test_it_can_call_underlying_model()
    {
        chiefRegister()->resource(NestableModelResourceStub::class);
        NestableModelResourceStub::migrateUp();

        $model = NestableModelStub::create(['id' => 'xxx', 'title' => 'custom title']);

        $this->assertEquals('custom title', $model->title);
        $this->assertEquals('foobar', $model->getCustomMethod());
    }

    public function test_it_can_get_localized_label()
    {
        chiefRegister()->resource(NestableModelResourceStub::class);
        NestableModelResourceStub::migrateUp();

        $node = NestableModelStub::create(['id' => 'xxx', 'title' => [
            'nl' => 'label nl',
            'fr' => 'label fr',
        ]]);

        app()->setLocale('nl');
        $this->assertEquals('label nl', $node->title);
        $this->assertEquals('label nl [offline]', $node->getNodeLabel()); // App label

        app()->setLocale('fr');
        $this->assertEquals('label fr', $node->title);
        $this->assertEquals('label fr [offline]', $node->getNodeLabel());
    }
}
