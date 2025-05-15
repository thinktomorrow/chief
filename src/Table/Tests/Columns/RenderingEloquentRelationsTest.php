<?php

namespace Thinktomorrow\Chief\Table\Tests\Columns;

use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Tests\Fixtures\ModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TaggedModelFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class RenderingEloquentRelationsTest extends TestCase
{
    public function test_it_can_render_belongs_to_many_relation()
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

    public function test_it_can_render_relation_attribute_from_a_belongs_to_relation(): void
    {
        ModelFixture::migrateUp();
        $model = ModelFixture::create(['title' => 'parent model']);
        $child = ModelFixture::create(['parent_id' => $model->id, 'title' => 'child model']);

        $column = ColumnText::make('parent.title')->model($child);

        $this->assertCount(1, $column->getItems());
        $this->assertEquals('parent model', $column->getItems()->first()->getValue());
    }

    public function test_no_items_are_resolved_if_relation_is_empty()
    {
        $column = ColumnText::make('tags')->model(new TaggedModelFixture);

        $this->assertCount(0, $column->getItems());
    }
}
