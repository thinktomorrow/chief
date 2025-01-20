<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Locales;

use Thinktomorrow\Chief\Forms\Fields\Locales\FieldLocales;
use Thinktomorrow\Chief\Forms\Fields\Locales\LocalizedField;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class LocalizedFieldTest extends TestCase
{
    private LocalizedField $localizedField;

    public function setUp(): void
    {
        parent::setUp();

        $this->localizedField = Text::make('title');

        config()->set('chief.sites', [
            ['handle' => 'nl', 'locale' => 'nl', 'fallbackLocale' => 'en'],
            ['handle' => 'fr', 'locale' => 'fr', 'fallbackLocale' => 'fr-be'],
        ]);
    }

    public function test_it_can_set_locales()
    {
        $this->localizedField->locales(['nl' => ['nl'], 'fr' => ['fr']]);

        $this->assertEquals(FieldLocales::fromArray([
            'nl' => ['nl'],
            'fr' => ['fr'],
        ]), $this->localizedField->getFieldLocales());
    }

    public function test_it_can_set_grouped_locales(): void
    {
        $locales = [
            'nl' => ['nl', 'en'],
            'fr' => ['fr', 'fr-be'],
        ];

        $this->localizedField->locales($locales);

        $this->assertEquals(FieldLocales::fromArray([
            'nl' => ['nl', 'en'],
            'fr' => ['fr', 'fr-be'],
        ]), $this->localizedField->getFieldLocales());
    }

    public function test_it_halts_explicit_empty_values(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $locales = ['en' => ['']];
        $this->localizedField->locales($locales);
    }

    public function test_it_halts_explicit_null_values(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $locales = ['fr' => [null]];
        $this->localizedField->locales($locales);
    }

    public function test_it_is_null_if_not_set(): void
    {
        $this->assertEquals(null, $this->localizedField->getFieldLocales());
    }

    public function test_it_can_check_if_field_is_localized(): void
    {
        $this->assertFalse($this->localizedField->hasLocales());

        $this->localizedField->locales(['en' => ['en']]);

        $this->assertTrue($this->localizedField->hasLocales());
    }

    public function test_it_sets_locales_when_model_is_set()
    {
        $model = new ArticlePage();

        $this->localizedField->locales()->model($model);
        $this->assertEquals(FieldLocales::fromArray([
            'nl' => ['nl'],
            'fr' => ['fr'],
        ]), $this->localizedField->getFieldLocales());
    }

    public function test_it_does_not_set_model_locales_when_locales_are_set()
    {
        $model = new ArticlePage();

        $this->localizedField->locales(['en' => ['en']]);
        $this->assertEquals(FieldLocales::fromArray(['en' => ['en']]), $this->localizedField->getFieldLocales());

        $this->localizedField->model($model);
        $this->assertEquals(FieldLocales::fromArray([
            'en' => ['en'],
        ]), $this->localizedField->getFieldLocales());
    }
}
