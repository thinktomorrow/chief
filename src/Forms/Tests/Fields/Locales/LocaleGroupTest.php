<?php

namespace Fields\Locales;

use Thinktomorrow\Chief\Forms\Fields\Locales\LocalizedField;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Tests\TestCase;
use Thinktomorrow\Chief\Sites\ChiefLocales;

class LocaleGroupTest extends TestCase
{
    private LocalizedField $localizedField;

    public function setUp(): void
    {
        parent::setUp();

        $this->localizedField = Text::make('title');

        config()->set('chief.sites', [
            ['id' => 'nl', 'locale' => 'nl', 'fallback_locale' => null],
            ['id' => 'fr-be', 'locale' => 'fr-be', 'fallback_locale' => 'fr'],
            ['id' => 'fr', 'locale' => 'fr', 'fallback_locale' => null],
        ]);
    }

    public function test_it_can_get_all_fallback_locales()
    {
        $this->assertEquals([
            'nl' => null,
            'fr-be' => 'fr',
            'fr' => null,
        ], ChiefLocales::fallbackLocales());
    }

    public function test_it_can_group_locales_by_fallback(): void
    {
        $this->localizedField->locales(['nl', 'fr', 'fr-be']);

        $this->assertEquals([
            'nl' => ['nl'],
            'fr' => ['fr', 'fr-be'],
        ], $this->localizedField->getLocaleGroups());
    }

    public function test_it_does_not_include_omitted_locales(): void
    {
        $this->localizedField->locales(['nl', 'fr-be']);

        $this->assertEquals([
            'nl' => ['nl'],
            'fr-be' => ['fr-be'],
        ], $this->localizedField->getLocaleGroups());
    }

    public function test_it_excludes_unknown_locales(): void
    {
        $this->localizedField->locales(['nl', 'de']);

        $this->assertEquals([
            'nl' => ['nl'],
        ], $this->localizedField->getLocaleGroups());
    }

    public function test_it_can_group_multiple_locales_by_fallback(): void
    {
        config()->set('chief.sites', [
            ['id' => 'nl', 'locale' => 'nl', 'fallback_locale' => 'fr'],
            ['id' => 'fr', 'locale' => 'fr', 'fallback_locale' => 'en'],
            ['id' => 'en', 'locale' => 'en', 'fallback_locale' => null],
        ]);

        $this->localizedField->locales(['nl', 'fr', 'en']);

        $this->assertEquals([
            'en' => ['en', 'nl', 'fr'],
        ], $this->localizedField->getLocaleGroups());
    }

    // Model influences...
}
