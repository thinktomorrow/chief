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
                'xxx.nl' => 'nl xxx',
                'xxx.en' => 'en xxx',
            ],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    public function test_file_with_one_locale_shows_attribute_without_locale(): void
    {
        $field = Text::make('xxx')
            ->locales(['nl'])
            ->rules('email');

        $this->assertEquals(
            [
                'xxx.nl' => 'xxx',
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
