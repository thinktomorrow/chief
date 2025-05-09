<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation\Input;

use Illuminate\Validation\Factory;
use InvalidArgumentException;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class GetsValidationRulesTest extends FormsTestCase
{
    public function test_field_has_default_no_validation()
    {
        $field = Text::make('xxx');

        $this->assertFalse($field->hasValidation());
        $this->assertEquals([], $field->getRules());
    }

    public function test_field_can_be_made_required()
    {
        $field = Text::make('xxx')->required();

        $this->assertTrue($field->hasValidation());
        $this->assertEquals(['required'], $field->getRules());
    }

    public function test_field_can_have_custom_validation()
    {
        $field = Text::make('xxx')
            ->rules('email');

        $this->assertTrue($field->hasValidation());
        $this->assertEquals(['nullable', 'email'], $field->getRules());
    }

    public function test_field_can_be_required_and_have_custom_validation()
    {
        $field = Text::make('xxx')
            ->required()
            ->rules('email');

        $this->assertTrue($field->hasValidation());
        $this->assertEquals(['required', 'email'], $field->getRules());
    }

    public function test_it_can_have_localized_rules()
    {
        $field = Text::make('xxx')
            ->locales(['nl', 'fr'])
            ->required()
            ->rules('email');

        $validator = $field->createValidatorInstance(app(Factory::class), []);

        $this->assertEquals([
            'xxx.nl' => ['required', 'email'],
            'xxx.fr' => ['required', 'email'],
        ], $validator->getRules());
    }

    public function test_a_custom_rule_attribute_is_not_supported()
    {
        $this->expectException(InvalidArgumentException::class);

        $field = Text::make('xxx')
            ->rules(['foobar' => ['required', 'max:200']]);
    }

    public function test_the_custom_name_is_used_as_validation_name()
    {
        $field = Text::make('content_trans')
            ->name('custom-name')
            ->locales(['nl', 'fr'])
            ->rules(['required', 'max:200']);

        $validator = $field->createValidatorInstance(app(Factory::class), []);

        $this->assertEquals([
            'custom-name.nl' => ['required', 'max:200'],
            'custom-name.fr' => ['required', 'max:200'],
        ], $validator->getRules());
    }
}
