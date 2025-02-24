<?php

namespace Thinktomorrow\Chief\Table\Tests;

use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Tests\Fixtures\ModelFixture;
use Thinktomorrow\Chief\Table\Tests\Fixtures\TaggedModelFixture;

class ColumnMapVariantTest extends TestCase
{
    public function test_it_can_map_variant()
    {
        ModelFixture::migrateUp();

        $column = ColumnText::make('title')
            ->mapVariant(fn ($value) => 'green')
            ->model(ModelFixture::create());

        $this->assertEquals('green', $column->getItems()->first()->getVariant());
    }

    public function test_it_can_map_each_relation_value()
    {
        TaggedModelFixture::migrateUp();
        $model = TaggedModelFixture::create();
        $model->tags()->create(['label' => 'first tag label']);
        $model->tags()->create(['label' => 'second tag label']);

        $column = ColumnText::make('tags')
            ->mapVariant(fn ($tag) => match ($tag->label) {
                'first tag label' => 'green',
                'second tag label' => 'blue',
            })
            ->model($model);

        $this->assertEquals('green', $column->getItems()->first()->getVariant());
        $this->assertEquals('blue', $column->getItems()[1]->getVariant());
    }

    public function test_it_can_map_variants_multiple_times()
    {
        $model = (object) ['books' => [
            ['title' => 'dutch title'],
            ['title' => 'English title'],
        ]];

        $column = ColumnText::make('books')
            ->mapVariant(fn ($book) => 'variant not used')
            ->mapVariant(fn ($book) => strtoupper($book['title']))
            ->model($model);

        $this->assertEquals('DUTCH TITLE', $column->getItems()->first()->getVariant());
        $this->assertEquals('ENGLISH TITLE', $column->getItems()[1]->getVariant());
    }

    public function test_it_can_map_value_via_array_mapping()
    {
        $model = (object) ['books' => [
            ['title' => 'dutch title'],
            ['title' => 'English title'],
        ]];

        $column = ColumnText::make('books.title')
            ->mapVariant([
                'dutch title' => 'DUTCH TITLE',
                'English title' => 'ENGLISH TITLE',
            ])
            ->model($model);

        $this->assertEquals('DUTCH TITLE', $column->getItems()->first()->getVariant());
        $this->assertEquals('ENGLISH TITLE', $column->getItems()[1]->getVariant());
    }
}
