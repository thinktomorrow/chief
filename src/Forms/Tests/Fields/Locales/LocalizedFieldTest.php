<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Locales;

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
            ['id' => 'nl', 'locale' => 'nl', 'fallbackLocale' => 'en'],
            ['id' => 'fr', 'locale' => 'fr', 'fallbackLocale' => 'fr-be'],
        ]);
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

    public function test_it_sets_locales_based_on_model_sites_when_model_is_set()
    {
        $model = new ArticlePage();
        $model->sites = ['fr'];

        $this->localizedField->locales()->model($model);
        $this->assertEquals(['fr'], $this->localizedField->getLocales());
    }

    public function test_it_does_not_set_model_locales_when_locales_are_already_set()
    {
        $model = new ArticlePage();

        $this->localizedField->locales(['en']);
        $this->assertEquals(['en'], $this->localizedField->getLocales());

        $this->localizedField->model($model);
        $this->assertEquals(['en'], $this->localizedField->getLocales());
    }
}
