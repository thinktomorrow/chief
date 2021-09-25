<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\FieldSet;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldSetTest extends TestCase
{
    /** @test */
    public function it_can_return_all_keys()
    {
        $fields = new FieldSet('xxx', [
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $this->assertEquals(['input-one','input-two'], $fields->keys());
    }

    /** @test */
    public function it_can_merge_two_fields_objects()
    {
        $fields = new FieldSet('xxx', [
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $fields2 = new FieldSet('xxx', [
            InputField::make('input-three'),
            InputField::make('input-four'),
        ]);

        $mergedFieldSet = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(4, $mergedFieldSet->all());
        $this->assertEquals(['input-one','input-two','input-three','input-four'], $mergedFieldSet->keys());
    }

    /** @test */
    public function similar_keys_are_overwritten_with_the_latter()
    {
        $fields = new FieldSet('xxx', [
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $fields2 = new FieldSet('xxx', [
            InputField::make('input-one'),
        ]);

        $mergedFieldSet = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(2, $mergedFieldSet->all());
        $this->assertEquals(['input-one','input-two'], $mergedFieldSet->keys());
    }

    /** @test */
    public function it_can_filter_by_any_value()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $this->assertCount(1, $fields->filterBy('name', 'input-one'));
        $this->assertEquals('input-one', $fields->filterBy('name', 'input-one')->first()->first()->getKey());
    }

    /** @test */
    public function it_can_filter_by_closure()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $filtered = $fields->filterBy(function ($field) {
            return $field->getName() == 'input-one';
        });

        $this->assertCount(1, $filtered);
        $this->assertEquals('input-one', $filtered->first()->first()->getKey());
    }
}
