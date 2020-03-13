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
    function it_can_get_the_dynamic_attributes_as_value()
    {
        $model = new ModelStub(['values' => ['title' => 'title value']]);

        $this->assertInstanceOf(DynamicAttributes::class, $model->values);
        $this->assertEquals(['title' => 'title value'], $model->values->all());
    }

    /** @test */
    function a_model_attribute_has_precedence_over_a_dynamic_attribute()
    {
        $model = new ModelStub(['title' => 'model title', 'values' => ['title' => 'title value']]);

        $this->assertEquals('model title', $model->title);
    }

    /** @test */
    function it_can_set_a_dynamic_attribute()
    {
        $model = new ModelStub(['values' => ['title' => 'title value']]);
        $model->title = 'new title value';

        $this->assertEquals('new title value', $model->dynamic('title'));
        $this->assertEquals('new title value', $model->title);
    }

    /** @test */
    function it_can_set_a_new_dynamic_attribute()
    {
        $model = new ModelStub(['values' => []]);
        $model->title = 'title value';

        $this->assertEquals('title value', $model->dynamic('title'));
        $this->assertEquals('title value', $model->title);
    }

    /** @test */
    function it_can_save_a_dynamic_attribute()
    {
        $model = new ModelStub(['values' => []]);
        $model->title = 'title value';
        $model->save();

        $model = ModelStub::first();

        $this->assertEquals('title value', $model->dynamic('title'));
        $this->assertEquals('title value', $model->title);
    }

    /** @test */
    function it_can_save_a_localized_dynamic_attribute()
    {
        $model = new ModelStub(['values' => []]);
        $model->title = 'title value';
        $model->save();

        $model = ModelStub::first();

        $this->assertEquals('title value', $model->dynamic('title'));
        $this->assertEquals('title value', $model->title);
    }
}
