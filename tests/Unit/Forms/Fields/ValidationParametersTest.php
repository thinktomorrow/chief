<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;
use Thinktomorrow\Chief\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ValidationParametersTest extends TestCase
{
    /** @test */
    public function fieldWithoutRulesReturnsEmptyArray()
    {
        $this->assertEmpty(ValidationParameters::make(Text::make('xxx'))->getRules());
    }

    /** @test */
    public function itCanCreateTheRulesArray()
    {
        $field = Text::make('xxx')->rules('email');

        $this->assertEquals(
            ['xxx' => ['nullable', 'email']],
            ValidationParameters::make($field)->getRules()
        );
    }

    /** @test */
    public function fieldWithBracketedNameHasExpectedDottedKeyFormat()
    {
        $field = Text::make('form[title]')->rules('email');

        $this->assertEquals(
            ['form.title' => ['nullable', 'email']],
            ValidationParameters::make($field)->getRules()
        );
    }

    /** @test */
    public function itCanCreateTheRulesArrayPerLocale()
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
    public function itForcesFileRules()
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
    public function itCanCreateTheRulesArrayPerLocaleForFiles()
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
    public function itCanCreateTheAttributesArray()
    {
        $field = Text::make('xxx')->rules('email');

        $this->assertEquals(
            ['xxx' => 'xxx'],
            ValidationParameters::make($field)->getAttributes()
        );
    }

    /** @test */
    public function itCanCreateACustomAttributesArray()
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
    public function itCanCreateTheLocalizedAttributesArray()
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
    public function itCanCreateACustomLocalizedAttributesArray()
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
