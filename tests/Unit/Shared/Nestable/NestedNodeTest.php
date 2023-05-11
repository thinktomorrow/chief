<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedNode;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestedNodeStub;

class NestedNodeTest extends TestCase
{
    use NestableTestHelpers;

    public function test_it_can_create_nestable_model()
    {
        $node = new NestedNodeStub(new NestableModelStub());

        $this->assertInstanceOf(NestedNode::class, $node);
        $this->assertInstanceOf(NestableModelStub::class, $node->getModel());
    }

    public function test_it_can_get_id_values()
    {
        $node = new NestedNodeStub(new NestableModelStub(['id' => '1', 'parent_id' => '5']));

        $this->assertEquals('1', $node->getId());
        $this->assertEquals('5', $node->getParentNodeId());

        $node = new NestedNodeStub(new NestableModelStub(['id' => '1']));

        $this->assertEquals('1', $node->getId());
        $this->assertNull($node->getParentNodeId());
    }

    public function test_it_can_call_underlying_model()
    {
        chiefRegister()->resource(NestableModelStub::class);
        NestableModelStub::migrateUp();

        $model = new NestedNodeStub(NestableModelStub::create(['id' => 'xxx', 'title' => 'custom title']));

        $this->assertEquals('custom title', $model->getModel()->title);
        $this->assertEquals('foobar', $model->getModel()->getCustomMethod());
    }

    public function test_it_can_get_localized_label()
    {
        chiefRegister()->resource(NestableModelStub::class);
        NestableModelStub::migrateUp();

        $node = new NestedNodeStub(NestableModelStub::create(['id' => 'xxx', 'title' => [
            'nl' => 'label nl',
            'fr' => 'label fr',
        ]]));

        app()->setLocale('nl');
        $this->assertEquals('label nl', $node->getModel()->title);
        $this->assertEquals('label nl [offline]', $node->getLabel()); // Admin label

        app()->setLocale('fr');
        $this->assertEquals('label fr', $node->getModel()->title);
        $this->assertEquals('label fr [offline]', $node->getLabel());
    }
}
