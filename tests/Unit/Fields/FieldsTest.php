<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\ManagedModels\Fields\FieldGroup;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldsTest extends TestCase
{
    /** @test */
    public function it_wraps_all_fields_in_a_formgroup()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $this->assertCount(2, $fields);
        $this->assertInstanceOf(FieldGroup::class, $fields[0]);
        $this->assertInstanceOf(FieldGroup::class, $fields[1]);
    }

    /** @test */
    public function it_accepts_a_formgroup()
    {
        $fields = $this->createFields();

        $this->assertCount(2, $fields);
        $this->assertEquals(collect($this->values()), $fields->all());
    }

    /** @test */
    public function it_can_check_if_there_is_any_field()
    {
        $fields = new Fields();

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
            'input-three' => InputField::make('input-three'),
            'input-four' => InputField::make('input-four'),
        ]), $fields->allFields());
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
        $this->assertEquals(InputField::make('input-three'), $fields->find('input-three'));
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

        $this->assertEquals(['input-one','input-two', 'input-three', 'input-four'], $fields->keys());
    }

    /** @test */
    public function it_can_filter_fields_by_key()
    {
        $fields = $this->createFields();

        $this->assertEquals(new Fields([
            InputField::make('input-two'),
            InputField::make('input-four'),
        ]), $fields->keyed(['input-two', 'input-four']));
    }

    /** @test */
    public function it_can_filter_fields_by_closure()
    {
        $fields = $this->createFields();

        $this->assertEquals(new Fields([
            InputField::make('input-two'),
        ]), $fields->filterBy(function($field){
            return $field->getKey() == 'input-two';
        }));
    }

    /** @test */
    public function it_can_filter_fields_by_tag()
    {
        $fields = new Fields([
            FieldGroup::make([
                $inputOne = InputField::make('input-one')->tag('foobar'),
                InputField::make('input-two'),
            ]),
            FieldGroup::make([
                $inputTwo = InputField::make('input-three')->tag('foobar'),
                InputField::make('input-four'),
            ])
        ]);

        $this->assertEquals(new Fields([
            $inputOne,
            $inputTwo,
        ]), $fields->tagged('foobar'));
    }

    /** @test */
    public function it_can_filter_fields_not_belonging_by_tag()
    {
        $fields = new Fields([
            FieldGroup::make([
                InputField::make('input-one')->tag('foobar'),
                $inputTwo = InputField::make('input-two'),
            ]),
            FieldGroup::make([
                InputField::make('input-three')->tag('foobar'),
                $inputFour = InputField::make('input-four'),
            ])
        ]);

        $this->assertEquals(new Fields([
            $inputTwo,
            $inputFour,
        ]), $fields->notTagged('foobar'));
    }

    /** @test */
    public function it_can_filter_by_untagged_fields()
    {
        $fields = new Fields([
            FieldGroup::make([
                InputField::make('input-one')->tag('foobar'),
                $inputTwo = InputField::make('input-two'),
            ]),
            FieldGroup::make([
                InputField::make('input-three')->tag('foobar'),
                $inputFour = InputField::make('input-four'),
            ])
        ]);

        $this->assertEquals(new Fields([
            $inputTwo,
            $inputFour,
        ]), $fields->untagged());
    }

    /** @test */
    public function it_adds_a_model_instance_to_each_field()
    {
        $fields = $this->createFields();

        $fields = $fields->model($articlePage = new ArticlePage());

        foreach($fields->allFields() as $field){
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
        $fields = $fields->remove(['input-two','input-four']);

        $this->assertCount(2, $fields->allFields());
    }

    /** @test */
    public function it_can_merge_two_fields_objects()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $fields2 = new Fields([
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
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $fields2 = new Fields([
            InputField::make('input-one'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(2, $mergedFields->all());
        $this->assertEquals(['input-two', 'input-one'], $mergedFields->keys());
    }

    private function createFields(): Fields
    {
        return new Fields($this->values());
    }

    private function values(): array
    {
        return [
            FieldGroup::make([
                InputField::make('input-one'),
                InputField::make('input-two'),
            ]),
            FieldGroup::make([
                InputField::make('input-three'),
                InputField::make('input-four'),
            ])
        ];
    }
}
