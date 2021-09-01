<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\FieldSet;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
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
        $this->assertInstanceOf(FieldSet::class, $fields[0]);
        $this->assertInstanceOf(FieldSet::class, $fields[1]);
    }

    /** @test */
    public function it_accepts_a_fieldSet()
    {
        $fields = $this->createFields($values = $this->values());

        $this->assertCount(2, $fields);
        $this->assertEquals(collect($values), $fields->all());
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

        $this->assertEquals(collect([
            'input-two' => InputField::make('input-two'),
            'input-four' => InputField::make('input-four'),
        ]), $fields->keyed(['input-two', 'input-four'])->allFields());
    }

    /** @test */
    public function it_can_filter_fields_by_closure()
    {
        $fields = $this->createFields();

        $this->assertEquals(collect([
            'input-two' => InputField::make('input-two'),
        ]), $fields->filterBy(function ($field) {
            return $field->getKey() == 'input-two';
        })->allFields());
    }

    /** @test */
    public function it_can_filter_fields_by_tag()
    {
        $fields = new Fields([
            FieldSet::make([
                $inputOne = InputField::make('input-one')->tag('foobar'),
                InputField::make('input-two'),
            ]),
            FieldSet::make([
                $inputThree = InputField::make('input-three')->tag('foobar'),
                InputField::make('input-four'),
            ]),
        ]);

        $this->assertEquals(collect([
            'input-one' => $inputOne,
            'input-three' => $inputThree,
        ]), $fields->tagged('foobar')->allFields());
    }

    /** @test */
    public function it_can_filter_fields_not_belonging_by_tag()
    {
        $fields = new Fields([
            FieldSet::make([
                InputField::make('input-one')->tag('foobar'),
                $inputTwo = InputField::make('input-two'),
            ]),
            FieldSet::make([
                InputField::make('input-three')->tag('foobar'),
                $inputFour = InputField::make('input-four'),
            ]),
        ]);

        $this->assertEquals(collect([
            'input-two' => $inputTwo,
            'input-four' => $inputFour,
        ]), $fields->notTagged('foobar')->allFields());
    }

    /** @test */
    public function it_can_filter_by_untagged_fields()
    {
        $fields = new Fields([
            FieldSet::make([
                InputField::make('input-one')->tag('foobar'),
                $inputTwo = InputField::make('input-two'),
            ]),
            FieldSet::make([
                InputField::make('input-three')->tag('foobar'),
                $inputFour = InputField::make('input-four'),
            ]),
        ]);

        $this->assertEquals(collect([
            'input-two' => $inputTwo,
            'input-four' => $inputFour,
        ]), $fields->untagged()->allFields());
    }

    /** @test */
    public function it_adds_a_model_instance_to_each_field()
    {
        $fields = $this->createFields();

        $fields = $fields->model($articlePage = new ArticlePage());

        foreach ($fields->allFields() as $field) {
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

    /** @test */
    public function it_can_create_field_group_via_yield()
    {
        $fields = new Fields([
            FieldSet::open(),
            InputField::make('input-one'),
            InputField::make('input-two'),
            FieldSet::close(),
        ]);

        $this->assertCount(1, $fields->all());
        $this->assertCount(2, $fields->allFields());
    }

    /** @test */
    public function it_can_create_multiple_field_groups_via_yield()
    {
        $fields = new Fields([
            FieldSet::open(),
            InputField::make('input-one'),
            InputField::make('input-two'),
            FieldSet::close(),
            InputField::make('input-three'),
            FieldSet::open(),
            InputField::make('input-four'),
            InputField::make('input-five'),
            FieldSet::close(),
        ]);

        $this->assertCount(3, $fields->all());
        $this->assertCount(5, $fields->allFields());
    }

    /** @test */
    public function obsolete_open_and_close_fieldSets_are_ignored()
    {
        $fields = new Fields([
            FieldSet::close(),
            FieldSet::open(),
            FieldSet::open(),
            InputField::make('input-one'),
            InputField::make('input-two'),
            FieldSet::close(),
            FieldSet::close(),
        ]);

        $this->assertCount(1, $fields->all());
        $this->assertCount(2, $fields->allFields());
    }

    /** @test */
    public function it_can_create_field_groups_via_yield_in_method()
    {
        $owner = new class() {
            public function fields(): iterable
            {
                yield FieldSet::open();
                yield InputField::make('input-one');
                yield InputField::make('input-two');
                yield FieldSet::close();
            }
        };

        $fields = Fields::make($owner->fields());

        $this->assertCount(1, $fields->all());
        $this->assertCount(2, $fields->allFields());
    }

    private function createFields(array $values = null): Fields
    {
        return new Fields($values ?: $this->values());
    }

    private function values(): array
    {
        return [
            FieldSet::make([
                InputField::make('input-one'),
                InputField::make('input-two'),
            ]),
            FieldSet::make([
                InputField::make('input-three'),
                InputField::make('input-four'),
            ]),
        ];
    }
}
