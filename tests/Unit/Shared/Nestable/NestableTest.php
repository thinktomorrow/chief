<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared\Nestable;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestedNode;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestedNodeStub;
use Thinktomorrow\Chief\Tests\Unit\Shared\Nestable\Stubs\NestableModelStub;

class NestableTest extends TestCase
{
    public function test_it_can_create_nestable_model()
    {
        $model = new NestedNodeStub(new NestableModelStub());

        $this->assertInstanceOf(NestedNode::class, $model);
        $this->assertInstanceOf(NestableModelStub::class, $model->getModel());
    }

    public function test_it_can_call_underlying_model_properties_and_methods()
    {
        NestableModelStub::migrateUp();

        $model = new NestedNodeStub(NestableModelStub::create(['id' => 'xxx', 'title' => 'custom title']));

        $this->assertEquals('custom title', $model->getModel()->title);
        $this->assertEquals('foobar', $model->getModel()->getCustomMethod());

        $this->assertEquals('custom title', $model->title);
        $this->assertEquals('foobar', $model->getCustomMethod());
    }

    public function test_it_when_underlying_model_has_same_property_or_method_the_node_one_is_used()
    {
        NestableModelStub::migrateUp();

        $model = new NestedNodeStub($record = NestableModelStub::create(['id' => 'xxx', 'title' => 'custom title']));

        $this->assertNotEquals($record->id, $model->getModel()->getId());
        $this->assertEquals($record->id, $model->getId());
    }

    public function test_it_can_get_localized_label()
    {
        NestableModelStub::migrateUp();

        $model = new NestedNodeStub(NestableModelStub::create(['id' => 'xxx', 'title' => [
            'nl' => 'label nl',
            'fr' => 'label fr',
        ]]));

        app()->setLocale('nl');
        $this->assertEquals('label nl', $model->title);

        app()->setLocale('fr');
        $this->assertEquals('label fr', $model->title);

        $this->assertEquals('label nl', $model->getLabel('nl'));
        $this->assertEquals('label fr', $model->getLabel('fr'));
    }
}
