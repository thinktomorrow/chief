<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Validation;

use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Forms\Tests\TestCase;

class ValidationParametersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

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
        $field = Text::make('xxx')
            ->locales(['nl', 'en'])
            ->rules('email');

        $this->assertEquals(
            [
                'xxx.nl' => ['nullable', 'email'],
                'xxx.en' => ['nullable', 'email'],
            ],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_only_creates_localized_rules_is_field_is_localized()
    {
        $field = Text::make('xxx')
            ->setScopedLocales(['nl', 'en']) // This is not setting locales but rather scoping the locales
            ->rules('email');

        $this->assertEquals(
            [
                'xxx' => ['nullable', 'email'],
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
        $field = File::make('xxx')
            ->locales(['nl', 'en'])
            ->rules('mimetypes:image/png,text/plain');

        $this->assertEquals(
            [
                'files.xxx.nl' => ['nullable', 'file_mimetypes:image/png,text/plain'],
                'files.xxx.en' => ['nullable', 'file_mimetypes:image/png,text/plain'],
            ],
            ValidationParameters::make($field)->getRules()
        );
    }

    public function test_it_can_create_the_attributes_array()
    {
        $field = Text::make('xxx')->rules('email');

        $this->assertEquals(
            ['xxx' => 'xxx'],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_it_can_create_a_custom_attributes_array()
    {
        $field = Text::make('xxx')
            ->validationAttribute('foobar')
            ->rules('email');

        $this->assertEquals(
            ['xxx' => 'foobar'],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_it_can_create_the_localized_attributes_array()
    {
        $field = Text::make('xxx')
            ->locales(['nl', 'en'])
            ->rules('email');

        $this->assertEquals(
            [
                'xxx.nl' => 'xxx NL',
                'xxx.en' => 'xxx EN',
            ],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_it_sets_validation_per_scoped_locale(): void
    {
        $field = Text::make('xxx')
            ->locales(['nl', 'en'])
            ->setScopedLocales(['nl'])
            ->rules('email');

        $this->assertEquals(
            [
                'xxx.nl' => 'xxx NL',
            ],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_it_can_create_a_custom_localized_attributes_array()
    {
        $field = Text::make('xxx')
            ->validationAttribute('foobar')
            ->rules('email');

        $this->assertEquals(
            ['xxx' => 'foobar'],
            ValidationParameters::make($field)->getAttributes()
        );
    }
}
