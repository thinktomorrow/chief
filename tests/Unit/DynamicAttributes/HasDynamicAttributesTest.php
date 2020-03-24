<?php

namespace Thinktomorrow\Chief\Tests\Unit\DynamicAttributes;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\DynamicAttributes\DynamicAttributes;

class HasDynamicAttributesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ModelStub::migrateUp();
    }

        /** @test */
    public function it_can_get_a_dynamic_attribute()
    {
        $model = new ModelStub(['values' => ['title' => 'title value']]);

        $this->assertEquals('title value', $model->title);
    }

    /** @test */
    public function it_can_get_the_dynamic_attributes_as_value()
    {
        $model = new ModelStub(['values' => ['title' => 'title value']]);

        $this->assertInstanceOf(DynamicAttributes::class, $model->values);
        $this->assertEquals(['title' => 'title value'], $model->values->all());
    }

    /** @test */
    public function a_model_attribute_is_forced_as_dynamic_when_its_set_as_dynamic_attribute()
    {
        $model = new ModelStub(['title' => 'model title']);

        $this->assertEquals('model title', $model->title);
        $this->assertEquals('model title', $model->dynamic('title'));

        $this->assertEquals(new DynamicAttributes(['title' => 'model title']), $model->values);
    }

    /** @test */
    public function a_model_can_set_option_to_allow_all_properties_to_be_dynamic()
    {
        $model = new FullDynamicModelStub(['content' => 'model content', 'title' => 'model title']);

        $this->assertEquals('model content', $model->content);
        $this->assertEquals('model content', $model->dynamic('content'));

        // own attribute remains untouched
        $this->assertEquals('model title', $model->title);
        $this->assertNull($model->dynamic('title'));

        $this->assertEquals(new DynamicAttributes(['content' => 'model content']), $model->values);
    }

    /** @test */
    public function it_can_set_a_dynamic_attribute()
    {
        $model = new ModelStub(['values' => ['title' => 'title value']]);
        $model->title = 'new title value';

        $this->assertEquals('new title value', $model->dynamic('title'));
        $this->assertEquals('new title value', $model->title);
    }

    /** @test */
    public function it_can_set_a_new_dynamic_attribute()
    {
        $model = new ModelStub(['values' => []]);
        $model->title = 'title value';

        $this->assertEquals('title value', $model->dynamic('title'));
        $this->assertEquals('title value', $model->title);
    }

    /** @test */
    public function it_can_save_a_dynamic_attribute()
    {
        $model = new ModelStub(['values' => []]);
        $model->title = 'title value';
        $model->save();

        $model = ModelStub::first();

        $this->assertEquals('title value', $model->dynamic('title'));
        $this->assertEquals('title value', $model->title);
    }
}
