<?php

namespace Thinktomorrow\Chief\Table\Tests\Columns;

use Thinktomorrow\Chief\Plugins\Tags\Domain\Model\TagModel;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Tests\Fixtures\ModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TaggedModelFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class MappingColumnValuesTest extends TestCase
{
    public function test_it_can_map_value()
    {
        ModelFixture::migrateUp();

        $column = ColumnText::make('title')
            ->mapValue(fn ($value) => strtoupper($value))
            ->model(ModelFixture::create(['title' => 'test title']));

        $this->assertCount(1, $column->getItems());
        $this->assertEquals('TEST TITLE', $column->getItems()->first()->getValue());
    }

    public function test_it_does_map_null_value()
    {
        ModelFixture::migrateUp();

        $column = ColumnText::make('xxx')
            ->mapValue(fn ($value) => $value)
            ->model(ModelFixture::create(['title' => 'test title']));

        $this->assertCount(1, $column->getItems());
        $this->assertNull($column->getItems()->first()->getValue());
    }

    public function test_it_can_map_each_relation_value()
    {
        TaggedModelFixture::migrateUp();
        $model = TaggedModelFixture::create();
        $model->tags()->create(['label' => 'first tag label']);
        $model->tags()->create(['label' => 'second tag label']);

        $column = ColumnText::make('tags')
            ->mapValue(fn (TagModel $tag) => $tag->label)
            ->model($model);

        $this->assertCount(2, $column->getItems());
        $this->assertEquals('first tag label', $column->getItems()->first()->getValue());
        $this->assertEquals('second tag label', $column->getItems()[1]->getValue());
    }

    public function test_it_can_map_relation_via_dotted_syntax()
    {
        TaggedModelFixture::migrateUp();
        $model = TaggedModelFixture::create();
        $model->tags()->create(['label' => 'first tag label']);
        $model->tags()->create(['label' => 'second tag label']);

        $column = ColumnText::make('tags.label')->model($model);

        $this->assertCount(2, $column->getItems());
        $this->assertEquals('first tag label', $column->getItems()->first()->getValue());
        $this->assertEquals('second tag label', $column->getItems()[1]->getValue());
    }

    public function test_it_can_map_object()
    {
        $model = (object) ['books' => [
            ['title' => 'dutch title'],
            ['title' => 'English title'],
        ]];

        $column = ColumnText::make('books.title')->model($model);

        $this->assertCount(2, $column->getItems());
        $this->assertEquals('dutch title', $column->getItems()->first()->getValue());
        $this->assertEquals('English title', $column->getItems()[1]->getValue());
    }

    public function test_it_can_map_values_multiple_times()
    {
        $model = (object) ['books' => [
            ['title' => 'dutch title'],
            ['title' => 'English title'],
        ]];

        $column = ColumnText::make('books')
            ->mapValue(fn ($book) => $book['title'])
            ->mapValue(fn ($title) => strtoupper($title))
            ->model($model);

        $this->assertCount(2, $column->getItems());
        $this->assertEquals('DUTCH TITLE', $column->getItems()->first()->getValue());
        $this->assertEquals('ENGLISH TITLE', $column->getItems()[1]->getValue());
    }

    public function test_it_can_map_value_via_array_mapping()
    {
        $model = (object) ['books' => [
            ['title' => 'dutch title'],
            ['title' => 'English title'],
        ]];

        $column = ColumnText::make('books.title')
            ->mapValue([
                'dutch title' => 'DUTCH TITLE',
                'English title' => 'ENGLISH TITLE',
            ])
            ->model($model);

        $this->assertCount(2, $column->getItems());
        $this->assertEquals('DUTCH TITLE', $column->getItems()->first()->getValue());
        $this->assertEquals('ENGLISH TITLE', $column->getItems()[1]->getValue());
    }
}
