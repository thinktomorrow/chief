<?php

namespace Thinktomorrow\Chief\Addons\Repeat\tests;

use Thinktomorrow\Chief\Addons\Repeat\RepeatField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\TestCase;

class RepeatUnitTest extends TestCase
{
    use TestHelpers;

    /** @test */
    public function it_can_create_a_repeat_field()
    {
        $field = RepeatField::make('foobar', [
            InputField::make('title'),
            InputField::make('content'),
        ]);

        $this->assertCount(2, $field->getFieldSet());
        $this->assertCount(1, $field->getRepeatedFields());
        $this->assertCount(2, $field->getRepeatedFields()->allFields());
    }

    /** @test */
    public function it_can_create_repeated_fields_per_existing_set()
    {
        $field = $this->createRepeatField();

        $this->assertCount(2, $field->getRepeatedFields()->all()); // 2 filled in sets

        // Count total of all fields. Cannot use allFields because due to similar keys of repeated fields these would get merged out.
        $this->assertEquals(4, array_reduce($field->getRepeatedFields()->all()->all(), fn ($carry, $fieldSet) => $carry + $fieldSet->count(), 0));

        $this->assertEquals('first title', $field->getRepeatedFields()->first()->first()->getValue());
        $this->assertEquals('first content', $field->getRepeatedFields()->first()->find('content')->getValue());

        $this->assertEquals('second title', $field->getRepeatedFields()[1]->first()->getValue());
        $this->assertEquals('second content', $field->getRepeatedFields()[1]->find('content')->getValue());
    }

    /** @test */
    public function it_can_use_localized_fields()
    {
        $field = $this->createRepeatField([
            [
                'title' => ['nl' => 'nl title', 'en' => 'en title'],
                'content' => ['nl' => 'nl content', 'en' => 'en content'],
            ],
        ], ['nl' ,'en']);

        app()->setLocale('nl');
        $this->assertEquals('nl title', $field->getRepeatedFields()->first()->first()->getValue());
        $this->assertEquals('en title', $field->getRepeatedFields()->first()->first()->getValue('en'));
        $this->assertEquals('nl content', $field->getRepeatedFields()->first()->find('content')->getValue());
        $this->assertEquals('en content', $field->getRepeatedFields()->first()->find('content')->getValue('en'));
    }

    /** @test */
    public function it_can_be_rendered()
    {
        $field = $this->createRepeatField();

        $this->assertStringContainsStringIgnoringCase('first title', $field->render());
        $this->assertStringContainsStringIgnoringCase('second content', $field->render());
    }

    /** @test */
    public function it_can_be_nested()
    {
        $field = RepeatField::make('productOptions', [
            InputField::make('Type'),
            RepeatField::make('values', [
                InputField::make('value'),
            ]),
        ]);

        $this->assertNotNull($field->render());
    }
}
