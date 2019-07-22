<?php

namespace Thinktomorrow\Chief\Tests\Feature\Fields;

use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldValidationTest extends TestCase
{
    /** @test */
    public function it_can_check_if_there_is_validation()
    {
        $field = InputField::make('content_trans');

        $this->assertFalse($field->hasValidation());
        $this->assertNull($field->getValidation());
    }

    /** @test */
    public function a_field_can_generate_translatable_validation_rules()
    {
        $field = InputField::make('content_trans')
            ->validation('required|max:200')
            ->translatable(['nl', 'fr']);

        $this->assertEquals([
            'trans.nl.content_trans' => ['required','max:200'],
            'trans.fr.content_trans' => ['required','max:200'],
        ], $field->validator([])->getRules());
    }

    /** @test */
    public function the_name_field_is_used_for_the_rules()
    {
        $field = InputField::make('content_trans')
            ->name('foobar')
            ->validation('required|max:200')
            ->translatable(['nl', 'fr']);

        $this->assertEquals([
            'trans.nl.foobar' => ['required','max:200'],
            'trans.fr.foobar' => ['required','max:200'],
        ], $field->validator([])->getRules());
    }

    /** @test */
    public function a_custom_rule_attribute_overrules_the_automatic_translation_injection()
    {
        $field = InputField::make('content_trans')
            ->validation(['foobar' => 'required|max:200'])
            ->translatable(['nl', 'fr']);

        $this->assertEquals([
            'foobar' => ['required','max:200'],
        ], $field->validator([])->getRules());
    }

    /** @test */
    public function a_name_with_a_placeholder_will_have_the_locales_replacing_the_asterisk()
    {
        $field = InputField::make('content_trans')
            ->name('foo.:locale.bar')
            ->validation('required|max:200')
            ->translatable(['nl', 'fr']);

        $this->assertEquals([
            'foo.nl.bar' => ['required','max:200'],
            'foo.fr.bar' => ['required','max:200'],
        ], $field->validator([])->getRules());
    }

    /** @test */
    public function when_request_payload_for_given_locale_is_empty_the_validation_ignores_this_locale()
    {
        $field = InputField::make('content_trans')
            ->name('foobar')
            ->validation('required|max:200')
            ->translatable(['nl', 'fr']);

        $rules = $field->validator(['trans' => [
            'nl' => ['foobar' => 'entry'],
            'fr' => ['foobar' => null],
        ]])->getRules();

        $this->assertEquals([
            'trans.nl.foobar' => ['required','max:200'],
        ], $rules);
    }
}
