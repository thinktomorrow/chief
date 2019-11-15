<?php

namespace Thinktomorrow\Chief\Tests\Feature\Fields;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldsFilterTest extends TestCase
{
    /** @test */
    public function it_can_filter_by_key_value()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(1, $fields->filterBy('key', 'input-one'));
        $this->assertEquals('input-one', $fields->filterBy('key', 'input-one')->first()->key);
    }

    /** @test */
    public function it_can_filter_by_any_value()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $this->assertCount(1, $fields->filterBy('name', 'input-one'));
        $this->assertEquals('input-one', $fields->filterBy('name', 'input-one')->first()->key);
    }

    /** @test */
    public function it_can_filter_by_closure()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $filtered = $fields->filterBy(function($field){ return $field->name == 'input-one';  });

        $this->assertCount(1, $filtered);
        $this->assertEquals('input-one', $filtered->first()->key);
    }
}
