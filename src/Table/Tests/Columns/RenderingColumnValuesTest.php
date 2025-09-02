<?php

namespace Thinktomorrow\Chief\Table\Tests\Columns;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Tests\Fixtures\ModelFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class RenderingColumnValuesTest extends TestCase
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

    public function test_it_can_render_localized_value(): void
    {
        $model = new ModelFixture(['dynamic_title' => ['nl' => 'foobar dynamic nl', 'en' => 'foobar dynamic en']]);

        $column = ColumnText::make('dynamic_title')->model($model);
        $this->assertEquals('foobar dynamic nl', $column->getItems()->first()->getValue('nl'));
        $this->assertEquals('foobar dynamic en', $column->getItems()->first()->getValue('en'));
    }

    public function test_value_originates_from_model_method()
    {
        $model = new ModelFixture;

        $column = ColumnText::make('categories')->model($model);

        $this->assertCount(2, $column->getItems());
        $this->assertEquals('first category', $column->getItems()->first()->getValue());
        $this->assertEquals('second category', $column->getItems()[1]->getValue());
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

    public function test_it_verifies_if_value_can_be_rendered(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $column = ColumnText::make('fake')->value(Text::make('cannot-be-rendered'));

        $column->getItems()->first()->getValue();
    }
}
