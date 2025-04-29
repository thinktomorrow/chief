<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation;

use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class ValidationParametersRulesTest extends FormsTestCase
{
    public function test_field_without_rules_returns_empty_array()
    {
        $this->assertEmpty(ValidationParameters::make(Text::make('xxx'))->getRules());
    }

    public function test_it_can_create_the_rules_array()
    {
        $field = Text::make('xxx')->rules('email');

        $this->assertEquals(
            ['xxx' => ['nullable', 'email']],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_field_with_bracketed_name_has_expected_dotted_key_format()
    {
        $field = Text::make('form[title]')->rules('email');

        $this->assertEquals(
            ['form.title' => ['nullable', 'email']],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_can_create_the_rules_array_per_locale()
    {
        $field = Text::make('xxx')->locales(['nl', 'en'])->rules('email');

        $this->assertEquals(
            [
                'xxx.nl' => ['nullable', 'email'],
                'xxx.en' => ['nullable', 'email'],
            ],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_forces_file_rules()
    {
        $field = File::make('xxx')->rules('mimetypes:image/png,text/plain');

        $this->assertEquals(
            [
                'files.xxx.nl' => ['nullable', 'file_mimetypes:image/png,text/plain'],
            ],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_can_create_the_rules_array_per_locale_for_files()
    {
        $field = File::make('xxx')->locales(['nl', 'en'])->rules('mimetypes:image/png,text/plain');

        $this->assertEquals(
            [
                'files.xxx.nl' => ['nullable', 'file_mimetypes:image/png,text/plain'],
                'files.xxx.en' => ['nullable', 'file_mimetypes:image/png,text/plain'],
            ],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_can_handle_multiple_values_in_rules()
    {
        $field = Text::make('xxx')->locales(['nl', 'en'])->rules('email');

        $this->assertEquals(
            [
                'xxx.nl.*' => ['nullable', 'email'],
                'xxx.en.*' => ['nullable', 'email'],
            ],
            ValidationParameters::make($field)->multiple()->getRules()
        );
    }

    public function test_it_applies_map_keys_callback_to_keys()
    {
        $field = Text::make('form[title]')->rules('email');

        $params = ValidationParameters::make($field)->mapKeys(fn ($key) => 'mapped.'.$key);

        $this->assertEquals(
            ['mapped.form.title' => ['nullable', 'email']],
            $params->getRules()
        );
    }

    public function test_it_returns_non_localized_rules_when_no_locales_are_present()
    {
        $field = Text::make('xxx')->rules('required');

        $this->assertEquals(
            ['xxx' => ['required']],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_returns_empty_array_if_value_is_empty_array()
    {
        $field = Text::make('xxx')->rules([]);

        $this->assertEquals(
            [],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_handles_no_locales_and_no_rules()
    {
        $field = Text::make('xxx');

        $this->assertEquals(
            [],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_handles_non_associative_array_rules_with_locales()
    {
        $field = Text::make('xxx')->locales(['nl', 'en'])->rules(['required', 'email']);

        $this->assertEquals(
            [
                'xxx.nl' => ['required', 'email'],
                'xxx.en' => ['required', 'email'],
            ],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_handles_rules_with_callback_map_keys_and_multiple()
    {
        $field = Text::make('form[title]')->locales(['nl', 'en'])->rules(['required']);

        $params = ValidationParameters::make($field)->mapKeys(fn ($key) => 'custom.'.$key)->multiple();

        $this->assertEquals(
            [
                'custom.form.title.nl.*' => ['required'],
                'custom.form.title.en.*' => ['required'],
            ],
            $params->getRules()
        );
    }

    public function test_it_returns_empty_when_value_is_empty_array_and_locales_present()
    {
        $field = Text::make('xxx')->locales(['nl', 'en'])->rules([]);

        $this->assertEquals(
            [],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_returns_correct_keys_when_locales_present_and_multiple_is_false()
    {
        $field = Text::make('xxx')->locales(['nl', 'en'])->rules('required');

        $params = ValidationParameters::make($field);

        $this->assertEquals(
            [
                'xxx.nl' => ['required'],
                'xxx.en' => ['required'],
            ],
            $params->getRules()
        );
    }
}
