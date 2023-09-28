<?php

namespace Fields;

use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Tests\TestCase;

class LocalizedFieldTest extends TestCase
{
    /** @test */
    public function when_localized_it_uses_a_localized_format_for_the_name()
    {
        $component = Text::make('title')->locales(['nl', 'en']);

        $this->assertEquals('title', $component->getId());
        $this->assertEquals('title', $component->getName());
        $this->assertEquals('trans[nl][title]', $component->getName('nl'));
        $this->assertEquals('trans[en][title]', $component->getName('en'));
        $this->assertEquals('title', $component->getColumnName());
    }

    /** @test */
    public function when_files_are_localized_a_specific_localized_format_is_used()
    {
        $component = File::make('image')->locales(['nl', 'en']);

        $this->assertEquals('image', $component->getId());
        $this->assertEquals('image', $component->getName());
        $this->assertEquals('files[image][nl]', $component->getName('nl'));
        $this->assertEquals('files[image][en]', $component->getName('en'));
        $this->assertEquals('image', $component->getColumnName());
    }

    /** @test */
    public function a_custom_name_is_used_as_localized_format_when_it_contains_a_locale_placeholder()
    {
        $field = Text::make('title')
            ->locales(['nl', 'en'])
            ->name('custom-title-:locale');

        $this->assertEquals('custom-title-:locale', $field->getName());
        $this->assertEquals('custom-title-nl', $field->getName('nl'));
        $this->assertEquals('custom-title-en', $field->getName('en'));
    }

    /** @test */
    public function custom_name_is_used_for_localized_name()
    {
        $field = Text::make('title')
            ->locales(['nl', 'en'])
            ->name('custom-title');

        $this->assertEquals('custom-title', $field->getName());
        $this->assertEquals('trans[nl][custom-title]', $field->getName('nl'));
        $this->assertEquals('trans[en][custom-title]', $field->getName('en'));
    }

    public function test_it_can_get_all_localized_keys()
    {
        $field = Text::make('title')
            ->locales(['nl', 'en']);

        $this->assertEquals([
            'trans.nl.title',
            'trans.en.title',
        ], $field->getLocalizedKeys());
    }

    public function test_it_can_get_all_localized_keys_by_custom_template()
    {
        $field = Text::make('title')
            ->setLocalizedFormKeyTemplate(':name.:locale')
            ->locales(['nl', 'en']);

        $this->assertEquals([
            'title.nl',
            'title.en',
        ], $field->getLocalizedKeys());
    }

    public function test_it_can_get_all_localized_names()
    {
        $field = Text::make('title')
            ->locales(['nl', 'en']);

        $this->assertEquals([
            'trans[nl][title]',
            'trans[en][title]',
        ], $field->getLocalizedNames());
    }

    public function test_it_can_get_all_localized_names_by_custom_template()
    {
        $field = Text::make('title')
            ->name('foobar')
            ->setLocalizedFormKeyTemplate(':name.:locale')
            ->locales(['nl', 'en']);

        $this->assertEquals([
            'foobar[nl]',
            'foobar[en]',
        ], $field->getLocalizedNames());
    }

    public function test_it_can_get_all_localized_dotted_names()
    {
        $field = Text::make('title')
            ->name('foobar')
            ->locales(['nl', 'en']);

        $this->assertEquals([
            'trans.nl.foobar',
            'trans.en.foobar',
        ], $field->getLocalizedDottedNames());
    }
}
