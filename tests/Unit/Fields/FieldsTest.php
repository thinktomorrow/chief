<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\TextField;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldsTest extends TestCase
{
    /** @test */
    public function it_accepts_a_field()
    {
        $fields = $this->createFields($values = $this->values());

        $this->assertCount(2, $fields);
        $this->assertEquals(collect($values), $fields->all());
    }

    /** @test */
    public function it_can_check_if_there_is_any_field()
    {
        $fields = Fields::make();

        $this->assertFalse($fields->any());
        $this->assertTrue($fields->isEmpty());

        $fields = $this->createFields();

        $this->assertTrue($fields->any());
        $this->assertFalse($fields->isEmpty());
    }

    /** @test */
    public function it_can_return_all_fields()
    {
        $fields = $this->createFields();

        $this->assertCount(2, $fields);
        $this->assertEquals(collect([
            'input-one' => InputField::make('input-one'),
            'input-two' => InputField::make('input-two'),
        ]), $fields->all());
    }

    /** @test */
    public function it_can_return_the_first_field()
    {
        $fields = $this->createFields();

        $this->assertEquals(InputField::make('input-one'), $fields->first());
    }

    /** @test */
    public function it_can_find_a_field_by_key()
    {
        $fields = $this->createFields();

        $this->assertEquals(InputField::make('input-one'), $fields->find('input-one'));
        $this->assertEquals(InputField::make('input-two'), $fields->find('input-two'));
    }

    /** @test */
    public function a_field_not_found_by_key_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        $fields = $this->createFields();

        $fields->find('input-five');
    }

    /** @test */
    public function it_can_return_all_keys()
    {
        $fields = $this->createFields();

        $this->assertEquals(['input-one','input-two'], $fields->keys());
    }

    /** @test */
    public function it_can_filter_fields_by_key()
    {
        $fields = $this->createFields();

        $this->assertEquals(collect([
            'input-two' => InputField::make('input-two'),
        ]), $fields->keyed(['input-two'])->all());
    }

    /** @test */
    public function it_can_filter_fields_by_closure()
    {
        $fields = $this->createFields();

        $this->assertEquals(collect([
            'input-two' => InputField::make('input-two'),
        ]), $fields->filterBy(function ($field) {
            return $field->getKey() == 'input-two';
        })->all());
    }

    /** @test */
    public function it_can_filter_fields_by_tag()
    {
        $fields = Fields::make([
            $inputOne = InputField::make('input-one')->tag('foobar'),
            InputField::make('input-two'),
            $inputThree = InputField::make('input-three')->tag('foobar'),
            InputField::make('input-four'),
        ]);

        $this->assertEquals(collect([
            'input-one' => $inputOne,
            'input-three' => $inputThree,
        ]), $fields->tagged('foobar')->all());
    }

    /** @test */
    public function it_can_filter_fields_not_belonging_by_tag()
    {
        $fields = Fields::make([
            InputField::make('input-one')->tag('foobar'),
            $inputTwo = InputField::make('input-two'),
            InputField::make('input-three')->tag('foobar'),
            $inputFour = InputField::make('input-four'),
        ]);

        $this->assertEquals(collect([
            'input-two' => $inputTwo,
            'input-four' => $inputFour,
        ]), $fields->notTagged('foobar')->all());
    }

    /** @test */
    public function it_can_filter_by_untagged_fields()
    {
        $fields = Fields::make([
            InputField::make('input-one')->tag('foobar'),
            $inputTwo = InputField::make('input-two'),
            InputField::make('input-three')->tag('foobar'),
            $inputFour = InputField::make('input-four'),
        ]);

        $this->assertEquals(collect([
            'input-two' => $inputTwo,
            'input-four' => $inputFour,
        ]), $fields->untagged()->all());
    }

    /** @test */
    public function it_adds_a_model_instance_to_each_field()
    {
        $fields = $this->createFields();

        $fields = $fields->model($articlePage = new ArticlePage());

        foreach ($fields->all() as $field) {
            $ref = new \ReflectionClass($field);
            $method = $ref->getMethod('getModel');
            $method->setAccessible(true);

            $this->assertEquals($articlePage, $method->invoke($field));
        }
    }

    /** @test */
    public function it_can_remove_by_keys()
    {
        $fields = $this->createFields();
        $fields = $fields->remove(['input-two']);

        $this->assertCount(1, $fields->all());
    }

    /** @test */
    public function it_can_merge_two_fields_objects()
    {
        $fields = Fields::make([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $fields2 = Fields::make([
            InputField::make('input-three'),
            InputField::make('input-four'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(4, $mergedFields->all());
        $this->assertEquals(['input-one','input-two','input-three','input-four'], $mergedFields->keys());
    }

    /** @test */
    public function similar_keys_are_overwritten_with_the_latter()
    {
        $fields = Fields::make([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $fields2 = Fields::make([
            TextField::make('input-one'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(2, $mergedFields->all());
        $this->assertEquals(['input-one','input-two'], $mergedFields->keys());

        // Assert the first input is overwritten
        $this->assertInstanceOf(TextField::class, $mergedFields->first());
    }

    /** @test */
    public function similar_keys_are_overwritten_with_the_latter_when_setting_custom_key()
    {
        $fields = Fields::make([
            InputField::make('first'),
            InputField::make('xxx'),
        ]);

        $fields2 = Fields::make([
            TextField::make('first')->key('xxx'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(2, $mergedFields->all());
        $this->assertEquals(['first','xxx'], $mergedFields->keys());

        // Assert the last input is overwritten
        $this->assertInstanceOf(InputField::class, $mergedFields['first']);
        $this->assertInstanceOf(TextField::class, $mergedFields['xxx']);
    }

    private function createFields(array $values = null): Fields
    {
        return Fields::make($values ?: $this->values());
    }

    private function values(): array
    {
        return [
            'input-one' => InputField::make('input-one'),
            'input-two' => InputField::make('input-two'),
        ];
    }
}
