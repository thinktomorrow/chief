<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;

class ValidationParametersTest extends TestCase
{
    /** @test */
    public function field_without_rules_returns_empty_array()
    {
        $this->assertEmpty(ValidationParameters::make(Text::make('xxx'))->getRules());
    }

    /** @test */
    public function it_can_create_the_rules_array()
    {
        $field = Text::make('xxx')->rules('email');

        $this->assertEquals(
            ['xxx' => ['nullable', 'email']],
            ValidationParameters::make($field)->getRules()
        );
    }

    /** @test */
    public function field_with_bracketed_name_has_expected_dotted_key_format()
    {
        $field = Text::make('form[title]')->rules('email');

        $this->assertEquals(
            ['form.title' => ['nullable', 'email']],
            ValidationParameters::make($field)->getRules()
        );
    }

    /** @test */
    public function it_can_create_the_rules_array_per_locale()
    {
        $field = Text::make('xxx')
            ->locales(['nl', 'en'])
            ->rules('email')
        ;

        $this->assertEquals(
            [
                'trans.nl.xxx' => ['nullable', 'email'],
                'trans.en.xxx' => ['nullable', 'email'],
            ],
            ValidationParameters::make($field)->getRules()
        );
    }

    /** @test */
    public function it_forces_file_rules()
    {
        $field = File::make('xxx')->rules('mimetypes:image/png,text/plain');

        $this->assertEquals(
            [
                'files.xxx.nl' => ['nullable', 'file_mimetypes:image/png,text/plain'],
            ],
            ValidationParameters::make($field)->getRules()
        );
    }

    /** @test */
    public function it_can_create_the_rules_array_per_locale_for_files()
    {
        $field = File::make('xxx')
            ->locales(['nl', 'en'])
            ->rules('mimetypes:image/png,text/plain')
        ;

        $this->assertEquals(
            [
                'files.xxx.nl' => ['nullable', 'file_mimetypes:image/png,text/plain'],
                'files.xxx.en' => ['nullable', 'file_mimetypes:image/png,text/plain'],
            ],
            ValidationParameters::make($field)->getRules()
        );
    }

    /** @test */
    public function it_can_create_the_attributes_array()
    {
        $field = Text::make('xxx')->rules('email');

        $this->assertEquals(
            ['xxx' => 'xxx'],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    /** @test */
    public function it_can_create_a_custom_attributes_array()
    {
        $field = Text::make('xxx')
            ->validationAttribute('foobar')
            ->rules('email')
        ;

        $this->assertEquals(
            ['xxx' => 'foobar'],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    /** @test */
    public function it_can_create_the_localized_attributes_array()
    {
        $field = Text::make('xxx')
            ->locales(['nl', 'en'])
            ->rules('email')
        ;

        $this->assertEquals(
            [
                'trans.nl.xxx' => 'xxx NL',
                'trans.en.xxx' => 'xxx EN',
            ],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    /** @test */
    public function it_can_create_a_custom_localized_attributes_array()
    {
        $field = Text::make('xxx')
            ->validationAttribute('foobar')
            ->rules('email')
        ;

        $this->assertEquals(
            ['xxx' => 'foobar'],
            ValidationParameters::make($field)->getAttributes()
        );
    }
}
