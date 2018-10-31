<?php

namespace Thinktomorrow\Chief\Tests\Feature\Fields;

use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldValidatorTest extends TestCase
{
    /** @test */
    function it_can_check_if_there_is_validation()
    {
        $field = InputField::make('content_trans');

        $this->assertFalse($field->hasValidation());
        $this->assertNull($field->getValidation());
    }

    /** @test */
    function a_field_can_generate_translatable_validation_rules()
    {
        $field = InputField::make('content_trans')
            ->validation('required|max:200')
            ->translatable(['nl', 'fr']);

        list('rules' => $rules) = $field->getValidation();

        $this->assertEquals([
            'trans.nl.content_trans' => 'required|max:200',
            'trans.fr.content_trans' => 'required|max:200',
        ],$rules);
    }

    /** @test */
    function the_name_field_is_used_for_the_rules()
    {
        $field = InputField::make('content_trans')
            ->name('foobar')
            ->validation('required|max:200')
            ->translatable(['nl', 'fr']);

        list('rules' => $rules) = $field->getValidation();

        $this->assertEquals([
            'trans.nl.foobar' => 'required|max:200',
            'trans.fr.foobar' => 'required|max:200',
        ],$rules);
    }

    /** @test */
    function a_custom_rule_attribute_overrules_the_automatic_translation_injection()
    {
        $field = InputField::make('content_trans')
            ->validation(['foobar' => 'required|max:200'])
            ->translatable(['nl', 'fr']);

        list('rules' => $rules) = $field->getValidation();

        $this->assertEquals([
            'foobar' => 'required|max:200',
        ],$rules);
    }

    /** @test */
    function a_name_with_an_asterisk_will_have_the_locales_replacing_the_asterisk()
    {
        $field = InputField::make('content_trans')
            ->name('foo.*.bar')
            ->validation('required|max:200')
            ->translatable(['nl', 'fr']);

        list('rules' => $rules) = $field->getValidation();

        $this->assertEquals([
            'foo.nl.bar' => 'required|max:200',
            'foo.fr.bar' => 'required|max:200',
        ],$rules);
    }

    /** @test */
    function when_request_payload_for_given_locale_is_empty_the_validation_ignores_this_locale()
    {
        $field = InputField::make('content_trans')
            ->name('foobar')
            ->validation('required|max:200')
            ->translatable(['nl', 'fr']);

        list('rules' => $rules) = $field->getValidation(['trans' => [
            'nl' => ['foobar' => 'entry'],
            'fr' => ['foobar' => null],
        ]]);

        $this->assertEquals([
            'trans.nl.foobar' => 'required|max:200',
        ],$rules);
    }
}
