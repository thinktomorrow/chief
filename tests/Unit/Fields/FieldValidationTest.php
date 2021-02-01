<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\ManagedModels\Fields\Validation\FieldValidator;

class FieldValidationTest extends TestCase
{
    /** @test */
    public function it_can_check_if_there_is_validation()
    {
        $field = InputField::make('content_trans');

        $this->assertFalse($field->hasValidation());
    }

    /** @test */
    public function a_field_can_generate_locales_validation_rules()
    {
        $field = InputField::make('content_trans')
            ->validation('required|max:200')
            ->locales(['nl', 'fr']);

        $validator = $this->invokePrivateMethod(app(FieldValidator::class), 'createValidator', [$field, []]);

        $this->assertEquals([
            'trans.nl.content_trans' => ['required','max:200'],
            'trans.fr.content_trans' => ['required','max:200'],
        ], $validator->getRules());
    }

    /** @test */
    public function the_name_field_is_used_for_the_rules()
    {
        $field = InputField::make('content_trans')
            ->name('foobar')
            ->validation('required|max:200')
            ->locales(['nl', 'fr']);

        $validator = $this->invokePrivateMethod(app(FieldValidator::class), 'createValidator', [$field, []]);

        $this->assertEquals([
            'trans.nl.foobar' => ['required','max:200'],
            'trans.fr.foobar' => ['required','max:200'],
        ], $validator->getRules());
    }

    /** @test */
    public function a_custom_rule_attribute_overrules_the_automatic_translation_injection()
    {
        $field = InputField::make('content_trans')
            ->validation(['foobar' => 'required|max:200'])
            ->locales(['nl', 'fr']);

        $validator = $this->invokePrivateMethod(app(FieldValidator::class), 'createValidator', [$field, []]);

        $this->assertEquals([
            'foobar' => ['required','max:200'],
        ], $validator->getRules());
    }

    /** @test */
    public function a_name_that_already_has_a_locale_placeholder_will_use_this_name_as_localized_format()
    {
        $field = InputField::make('content_trans')
            ->name('foo.:locale.bar')
            ->validation('required|max:200')
            ->locales(['nl', 'fr']);

        $validator = $this->invokePrivateMethod(app(FieldValidator::class), 'createValidator', [$field, []]);

        $this->assertEquals([
            'foo.nl.bar' => ['required','max:200'],
            'foo.fr.bar' => ['required','max:200'],
        ], $validator->getRules());
    }

    /** @test */
    public function when_request_payload_for_given_locale_is_empty_the_validation_ignores_this_locale()
    {
        $field = InputField::make('content_trans')
            ->name('foobar')
            ->validation('required|max:200')
            ->locales(['nl', 'fr']);

        $validator = $this->invokePrivateMethod(app(FieldValidator::class), 'createValidator', [$field, ['trans' => [
            'nl' => ['foobar' => 'entry'],
            'fr' => ['foobar' => null],
        ]]]);

        $this->assertEquals([
            'trans.nl.foobar' => ['required','max:200'],
        ], $validator->getRules());
    }

    /** @test */
    public function it_can_check_if_a_field_is_optional()
    {
        $field = InputField::make('content_trans');
        $this->assertFalse($field->required());

        $field = InputField::make('content_trans')->validation('required');
        $this->assertTrue($field->required());
    }
}
