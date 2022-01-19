<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Validation\FieldValidator;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldValidationTest extends TestCase
{
    /** @test */
    public function field_has_default_no_validation()
    {
        $field = Text::make('xxx');

        $this->assertFalse($field->hasValidation());
        $this->assertEquals([], $field->getRules());
    }

    /** @test */
    public function field_can_be_made_required()
    {
        $field = Text::make('xxx')->required();

        $this->assertTrue($field->hasValidation());
        $this->assertEquals(['required'], $field->getRules());
    }

    /** @test */
    public function field_can_have_custom_validation()
    {
        $field = Text::make('xxx')
            ->rules('email');

        $this->assertTrue($field->hasValidation());
        $this->assertEquals(['nullable', 'email'], $field->getRules());
    }

    /** @test */
    public function field_can_be_required_and_have_custom_validation()
    {
        $field = Text::make('xxx')
            ->required()
            ->rules('email');

        $this->assertTrue($field->hasValidation());
        $this->assertEquals(['required', 'email'], $field->getRules());
    }

    /** @test */
    public function it_can_have_localized_rules()
    {
        $field = Text::make('xxx')
            ->locales(['nl','fr'])
            ->required()
            ->rules('email');

        $validator = $this->invokePrivateMethod(app(FieldValidator::class), 'createValidator', [$field, []]);

        $this->assertEquals([
            'trans.nl.xxx' => ['required','email'],
            'trans.fr.xxx' => ['required','email'],
        ], $validator->getRules());
    }

    /** @test */
    public function a_custom_rule_attribute_is_not_supported()
    {
        $this->expectException(\InvalidArgumentException::class);

        $field = Text::make('xxx')
            ->rules(['foobar' => ['required', 'max:200']]);
    }

    /** @test */
    public function the_custom_name_is_used_as_validation_name()
    {
        $field = Text::make('content_trans')
            ->name('foo.:locale.bar')
            ->rules(['required','max:200'])
            ->locales(['nl', 'fr']);

        $validator = $this->invokePrivateMethod(app(FieldValidator::class), 'createValidator', [$field, []]);

        $this->assertEquals([
            'foo.nl.bar' => ['required','max:200'],
            'foo.fr.bar' => ['required','max:200'],
        ], $validator->getRules());
    }
}
