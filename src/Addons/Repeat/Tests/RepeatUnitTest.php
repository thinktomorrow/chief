<?php

namespace Thinktomorrow\Chief\Addons\Repeat\Tests;

use Illuminate\Support\Facades\View;
use Thinktomorrow\Chief\Addons\Repeat\RepeatField;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
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

        $this->assertCount(2, $field->getFields());
        $this->assertCount(1, $field->getRepeatedFields());
        $this->assertCount(2, $field->getRepeatedFields()[0]);
    }

    /** @test */
    public function it_can_create_repeated_fields_per_existing_set()
    {
        $field = $this->createRepeatField();

        $this->assertCount(2, $field->getRepeatedFields()); // 2 filled in sets

        // Count total of all fields.
        $this->assertEquals(4, array_reduce($field->getRepeatedFields(), fn ($carry, Fields $fields) => $carry + $fields->count(), 0));

        $this->assertEquals('first title', $field->getRepeatedFields()[0]->first()->getValue());
        $this->assertEquals('first content', $field->getRepeatedFields()[0]->find('content')->getValue());

        $this->assertEquals('second title', $field->getRepeatedFields()[1]->find('title')->getValue());
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
        $this->assertEquals('nl title', $field->getRepeatedFields()[0]->first()->getValue());
        $this->assertEquals('en title', $field->getRepeatedFields()[0]->first()->getValue('en'));
        $this->assertEquals('nl content', $field->getRepeatedFields()[0]->find('content')->getValue());
        $this->assertEquals('en content', $field->getRepeatedFields()[0]->find('content')->getValue('en'));
    }

    /** @test */
    public function it_can_be_rendered()
    {
        $field = $this->createRepeatField();

//        View::share('model', new ArticlePage());

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
