<?php

namespace Thinktomorrow\Chief\Table\Tests;

use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Tests\Fixtures\ModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TaggedModelFixture;

class ColumnItemValueTest extends TestCase
{
    public function test_default_value()
    {
        $column = ColumnText::make('fake')->default('foobar');

        $this->assertEquals('foobar', $column->getItems()->first()->getValue());
    }

    public function test_default_value_is_null_by_default()
    {
        $column = ColumnText::make('fake');

        $this->assertNull($column->getItems()->first()->getValue());
    }

    public function test_value_originates_from_model_attribute()
    {
        $model = new ModelFixture(['title' => 'foobar title']);

        $column = ColumnText::make('title')->model($model);

        $this->assertEquals('foobar title', $column->getItems()->first()->getValue());
    }

    public function test_value_originates_from_model_dynamic_attribute()
    {
        $model = new ModelFixture(['dynamic_title' => 'foobar dynamic']);

        $column = ColumnText::make('dynamic_title')->model($model);

        $this->assertEquals('foobar dynamic', $column->getItems()->first()->getValue());
    }

    public function test_value_originates_from_model_method()
    {
        $model = new ModelFixture;

        $column = ColumnText::make('categories')->model($model);

        $this->assertCount(2, $column->getItems());
        $this->assertEquals('first category', $column->getItems()->first()->getValue());
        $this->assertEquals('second category', $column->getItems()[1]->getValue());
    }

    public function test_value_originates_from_model_relation()
    {
        TaggedModelFixture::migrateUp();
        $model = TaggedModelFixture::create();
        $tag1 = $model->tags()->create(['label' => 'first tag label']);
        $tag2 = $model->tags()->create(['label' => 'second tag label']);

        $column = ColumnText::make('tags')->model($model);

        $this->assertCount(2, $column->getItems());
        $this->assertInstanceOf(TagModel::class, $column->getItems()->first()->getValue());
        $this->assertEquals($tag1->id, $column->getItems()->first()->getValue()->id);
        $this->assertEquals($tag2->id, $column->getItems()[1]->getValue()->id);
    }

    public function test_no_items_are_resolved_if_relation_is_empty()
    {
        $column = ColumnText::make('tags')->model(new TaggedModelFixture);

        $this->assertCount(0, $column->getItems());
    }

    public function test_value_originates_from_array()
    {
        $model = (object) (['titles' => [
            'nl' => 'dutch title',
            'en' => 'English title',
        ]]);

        $column = ColumnText::make('titles')->model($model);

        $this->assertCount(2, $column->getItems());
        $this->assertEquals('dutch title', $column->getItems()->first()->getValue());
        $this->assertEquals('English title', $column->getItems()[1]->getValue());
    }
}
