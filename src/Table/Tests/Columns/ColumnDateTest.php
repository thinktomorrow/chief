<?php

namespace Thinktomorrow\Chief\Table\Tests\Columns;

use Carbon\Carbon;
use Thinktomorrow\Chief\Table\Columns\ColumnDate;
use Thinktomorrow\Chief\Table\Tests\Fixtures\ModelFixture;
use Thinktomorrow\Chief\Table\Tests\TestCase;

class ColumnDateTest extends TestCase
{
    public function test_default_value_is_null()
    {
        $column = ColumnDate::make('published_at');

        $this->assertNull($column->getItems()->first()->getValue());
    }

    public function test_it_can_render_date_from_string()
    {
        $model = new ModelFixture(['published_at' => '2025-06-30 15:00:00']);

        $column = ColumnDate::make('published_at')->model($model);

        $this->assertEquals('2025-06-30 15:00', $column->getItems()->first()->getValue());
    }

    public function test_it_can_render_date_from_carbon_instance()
    {
        $carbon = Carbon::create(2025, 6, 30, 10, 45);

        $model = new ModelFixture(['published_at' => $carbon]);

        $column = ColumnDate::make('published_at')->model($model);

        $this->assertEquals('2025-06-30 10:45', $column->getItems()->first()->getValue());
    }

    public function test_it_returns_original_value_if_not_a_date()
    {
        $model = new ModelFixture(['published_at' => 'not-a-date']);

        $column = ColumnDate::make('published_at')->model($model);

        $this->assertEquals('not-a-date', $column->getItems()->first()->getValue());
    }

    public function test_it_can_use_custom_format()
    {
        $carbon = Carbon::create(2025, 6, 30, 10, 45);
        $model = new ModelFixture(['published_at' => $carbon]);

        $column = ColumnDate::make('published_at')->model($model)->format('d/m/Y');

        $this->assertEquals('30/06/2025', $column->getItems()->first()->getValue());
    }

    public function test_it_can_render_invalid_date(): void
    {
        $column = ColumnDate::make('published_at')->value('not-a-date -> something');

        $this->assertEquals('not-a-date -> something', $column->getItems()->first()->getValue());
    }

    public function test_replicated_items_inherit_format()
    {
        $carbon = Carbon::create(2025, 6, 30, 10, 45);
        $model = new ModelFixture(['published_at' => $carbon]);

        $column = ColumnDate::make('published_at')->format('d-m-Y H:i')->model($model);

        $this->assertEquals('30-06-2025 10:45', $column->getItems()->first()->getValue());
    }
}
