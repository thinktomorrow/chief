<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Locales;

use Thinktomorrow\Chief\Forms\Fields\Locales\LocalizedField;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;
use Thinktomorrow\Chief\Sites\ChiefSites;

class LocalizedFieldFormsTest extends FormsTestCase
{
    private LocalizedField $localizedField;

    protected function setUp(): void
    {
        parent::setUp();

        $this->localizedField = Text::make('title');

        config()->set('chief.sites', [
            ['locale' => 'en', 'fallback_locale' => null],
            ['locale' => 'nl', 'fallback_locale' => 'en'],
            ['locale' => 'fr', 'fallback_locale' => 'nl'],
        ]);

        ChiefSites::clearCache();
    }

    public function test_it_can_set_locales()
    {
        $locales = ['nl', 'fr'];

        $this->localizedField->locales($locales);

        $this->assertEquals($locales, $this->localizedField->getLocales());
    }

    public function test_it_can_check_if_field_is_localized(): void
    {
        $this->assertFalse($this->localizedField->hasLocales());

        $this->localizedField->locales(['en']);

        $this->assertTrue($this->localizedField->hasLocales());
    }

    public function test_it_can_set_and_get_locales()
    {
        $locales = ['nl', 'fr'];

        $this->localizedField->locales($locales);

        $this->assertEquals($locales, $this->localizedField->getLocales());
    }

    public function test_it_can_override_locales()
    {
        $this->localizedField->locales(['nl', 'en'])->setLocales(['fr']);
        $this->assertEquals(['fr'], $this->localizedField->getLocales());
    }

    public function test_it_can_get_fallback_locale()
    {
        $this->localizedField->locales(['nl', 'fr', 'en']);

        $this->assertEquals('en', $this->localizedField->getFallbackLocale('nl'));
        $this->assertEquals('nl', $this->localizedField->getFallbackLocale('fr'));
        $this->assertNull($this->localizedField->getFallbackLocale('en')); // No fallback
    }

    public function test_it_can_get_fallback_locale_only_when_its_of_own_locales()
    {
        $this->localizedField->locales(['nl', 'fr']);

        $this->assertNull($this->localizedField->getFallbackLocale('nl'));
        $this->assertEquals('nl', $this->localizedField->getFallbackLocale('fr'));
    }

    public function test_it_can_generate_localized_keys()
    {
        $this->localizedField->locales(['nl', 'fr']);
        $keys = $this->localizedField->getLocalizedKeys();

        $this->assertContains('title.nl', $keys);
        $this->assertContains('title.fr', $keys);
    }

    public function test_it_can_generate_bracketed_localized_names()
    {
        $this->localizedField->locales(['nl', 'fr']);
        $names = $this->localizedField->getBracketedLocalizedNames();

        $this->assertContains('title[nl]', $names);
        $this->assertContains('title[fr]', $names);
    }

    public function test_it_can_generate_dotted_localized_names()
    {
        $this->localizedField->locales(['nl', 'fr']);
        $names = $this->localizedField->getDottedLocalizedNames();

        $this->assertContains('title.nl', $names);
        $this->assertContains('title.fr', $names);
    }

    public function test_deprecated_methods_still_return_correct_values()
    {
        $this->localizedField->locales(['nl']);

        $this->assertEquals(
            $this->localizedField->getLocalizedFormKey(),
            $this->localizedField->getFieldName()
        );

        $this->assertEquals(
            $this->localizedField->getLocalizedFormKeyTemplate(),
            $this->localizedField->getFieldNameTemplate()
        );
    }
}
