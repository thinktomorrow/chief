<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Locales;

use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldName;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Locales\LocalizedField;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Tests\TestCase;

class LocalizedNameTest extends TestCase
{
    private LocalizedField $localizedField;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('chief.sites', [
            ['id' => 'nl', 'locale' => 'nl', 'fallbackLocale' => 'en'],
            ['id' => 'fr', 'locale' => 'fr', 'fallbackLocale' => 'fr-be'],
        ]);

        $this->localizedField = Text::make('title')->setFieldNameTemplate(':name.:locale');
    }

    public function test_it_can_set_and_retrieve_localized_form_key_template(): void
    {
        $template = 'field.:locale';

        $this->localizedField->setFieldNameTemplate($template);

        $localizedFormKey = $this->localizedField->getFieldName();

        $this->assertInstanceOf(FieldName::class, $localizedFormKey);
        $this->assertSame($template, $localizedFormKey->getTemplate());
    }

    public function test_it_generates_bracketed_localized_names(): void
    {
        $this->localizedField->locales(['nl', 'fr']);

        $bracketedNames = $this->localizedField->getBracketedLocalizedNames();

        $this->assertSame(['nl' => 'title[nl]', 'fr' => 'title[fr]'], $bracketedNames);
    }

    public function test_it_generates_dotted_localized_names(): void
    {
        $this->localizedField->locales(['nl', 'fr']);

        $dottedNames = $this->localizedField->getDottedLocalizedNames();

        $this->assertSame(['nl' => 'title.nl', 'fr' => 'title.fr'], $dottedNames);
    }

    public function test_it_generates_bracketed_localized_names_from_multiple_locales(): void
    {
        $this->localizedField->locales(['nl', 'nl-be', 'fr']);

        $bracketedNames = $this->localizedField->getBracketedLocalizedNames();

        $this->assertSame(['nl' => 'title[nl]', 'nl-be' => 'title[nl-be]', 'fr' => 'title[fr]'], $bracketedNames);
    }

    public function test_it_generates_dotted_localized_names_from_multiple_locales(): void
    {
        $this->localizedField->locales(['nl', 'nl-be', 'fr']);

        $dottedNames = $this->localizedField->getDottedLocalizedNames();

        $this->assertSame(['nl' => 'title.nl', 'nl-be' => 'title.nl-be', 'fr' => 'title.fr'], $dottedNames);
    }

    public function test_when_localized_it_uses_a_localized_format_for_the_name()
    {
        $component = Text::make('title')->locales(['nl', 'fr']);

        $this->assertEquals('title', $component->getId());
        $this->assertEquals('title', $component->getName());
        $this->assertEquals('title[nl]', $component->getName('nl'));
        $this->assertEquals('title[fr]', $component->getName('fr'));
        $this->assertEquals('title', $component->getColumnName());
    }

    public function test_when_localized_it_uses_a_custom_localized_format_for_the_name()
    {
        $component = Text::make('title')->setFieldNameTemplate('trans.:locale.:name')->locales(['nl', 'fr']);

        $this->assertEquals('trans[nl][title]', $component->getName('nl'));
        $this->assertEquals('trans[fr][title]', $component->getName('fr'));
    }

    public function test_when_files_are_localized_a_specific_localized_format_is_used()
    {
        $component = File::make('image')->locales(['nl', 'fr']);

        $this->assertEquals('image', $component->getId());
        $this->assertEquals('files[image]', $component->getName());
        $this->assertEquals('files[image][nl]', $component->getName('nl'));
        $this->assertEquals('files[image][fr]', $component->getName('fr'));
        $this->assertEquals('image', $component->getColumnName());
    }

    public function test_a_custom_name_is_used_as_localized_format_when_it_contains_a_locale_placeholder()
    {
        $field = Text::make('title')
            ->locales(['nl', 'fr'])
            ->name('custom-title-:locale');

        $this->assertEquals('custom-title-', $field->getName());
        $this->assertEquals('custom-title-nl', $field->getName('nl'));
        $this->assertEquals('custom-title-fr', $field->getName('fr'));
    }

    public function test_custom_name_is_used_for_localized_name()
    {
        $field = Text::make('title')
            ->locales(['nl', 'fr'])
            ->setFieldNameTemplate('trans.:locale.:name')
            ->name('custom-title');

        $this->assertEquals('trans[custom-title]', $field->getName());
        $this->assertEquals('trans[nl][custom-title]', $field->getName('nl'));
        $this->assertEquals('trans[fr][custom-title]', $field->getName('fr'));
    }

    public function test_it_can_get_all_localized_keys()
    {
        $field = Text::make('title')
            ->locales(['nl', 'fr']);

        $this->assertEquals([
            'nl' => 'title.nl',
            'fr' => 'title.fr',
        ], $field->getLocalizedKeys());
    }

    public function test_it_can_get_all_localized_keys_by_custom_template()
    {
        $field = Text::make('title')
            ->setFieldNameTemplate(':name.:locale')
            ->locales(['nl', 'fr']);

        $this->assertEquals([
            'nl' => 'title.nl',
            'fr' => 'title.fr',
        ], $field->getLocalizedKeys());
    }

    public function test_it_can_get_all_localized_names()
    {
        $field = Text::make('title')
            ->locales(['nl', 'fr']);

        $this->assertEquals([
            'nl' => 'title[nl]',
            'fr' => 'title[fr]',
        ], $field->getBracketedLocalizedNames());
    }

    public function test_it_can_get_all_localized_names_by_custom_template()
    {
        $field = Text::make('title')
            ->name('foobar')
            ->setFieldNameTemplate(':name.:locale')
            ->locales(['nl', 'fr']);

        $this->assertEquals([
            'nl' => 'foobar[nl]',
            'fr' => 'foobar[fr]',
        ], $field->getBracketedLocalizedNames());
    }

    public function test_it_can_get_all_localized_dotted_names()
    {
        $field = Text::make('title')
            ->name('foobar')
            ->locales(['nl', 'fr']);

        $this->assertEquals([
            'nl' => 'foobar.nl',
            'fr' => 'foobar.fr',
        ], $field->getDottedLocalizedNames());
    }
}
