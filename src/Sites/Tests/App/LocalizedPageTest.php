<?php

namespace Thinktomorrow\Chief\Sites\Tests\App;

use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\Tests\Fixtures\LocalizedPageFixture;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class LocalizedPageTest extends ChiefTestCase
{
    private LocalizedPageFixture $model;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('chief.sites', [
            ['locale' => 'nl', 'fallback_locale' => null, 'primary' => true], // First is primary
            ['locale' => 'fr', 'fallback_locale' => 'nl'],
            ['locale' => 'en', 'fallback_locale' => 'fr'],
        ]);

        LocalizedPageFixture::migrateUp();

        $this->model = new LocalizedPageFixture;

        $this->model->setSiteLocales(['nl', 'en']);
        $this->model->save();
    }

    public function test_it_has_locales_based_on_sites(): void
    {
        $this->model->fresh();

        $this->assertEquals(['nl', 'en'], $this->model->getSiteLocales());
    }

    public function test_it_has_all_site_locales_when_no_locales_are_present(): void
    {
        $this->model->setSiteLocales([]);
        $this->model->save();

        $this->model->fresh();

        $this->assertEquals(['nl', 'fr', 'en'], $this->model->getSiteLocales());

    }

    public function test_it_can_get_localized_value()
    {
        $this->model->setDynamic('title', 'Nederlandse titel', 'nl');
        $this->model->setDynamic('title', 'English title', 'en');

        app()->setLocale('en');
        $this->assertEquals('English title', $this->model->title);

        app()->setLocale('nl');
        $this->assertEquals('Nederlandse titel', $this->model->title);
    }

    public function test_it_gets_fallback_value_when_value_is_missing(): void
    {
        ChiefSites::clearCache();
        config()->set('chief.sites', [
            ['locale' => 'nl', 'fallback_locale' => null],
            ['locale' => 'en', 'fallback_locale' => 'nl'],
        ]);

        $this->model->fresh();

        $this->model->setDynamic('title', 'Nederlandse titel', 'nl');

        app()->setLocale('en');
        $this->assertEquals('Nederlandse titel', $this->model->title);
    }

    public function test_it_gets_fallback_value_when_value_is_null(): void
    {
        $this->model->setDynamic('title', 'Nederlandse titel', 'nl');
        $this->model->setDynamic('title', null, 'en');

        app()->setLocale('en');
        $this->assertEquals('Nederlandse titel', $this->model->title);
    }

    public function test_it_gets_own_value_when_value_is_empty_string(): void
    {
        $this->model->setDynamic('title', 'Nederlandse titel', 'nl');
        $this->model->setDynamic('title', '', 'en');

        app()->setLocale('en');
        $this->assertEquals('', $this->model->title);
    }

    public function test_it_gets_null_when_locale_is_missing_and_no_fallback_is_provided(): void
    {
        ChiefSites::clearCache();
        config()->set('chief.sites', [
            ['locale' => 'nl', 'fallback_locale' => null],
            ['locale' => 'en', 'fallback_locale' => null],
        ]);

        $this->model->setDynamic('title', 'Nederlandse titel', 'nl');

        app()->setLocale('en');
        $this->assertNull($this->model->title);
    }

    public function test_when_it_does_not_have_fallback_value(): void
    {
        app()->setLocale('en');
        $this->assertNull($this->model->title);
    }

    public function test_when_it_does_not_have_fallback(): void
    {
        ChiefSites::clearCache();
        config()->set('chief.sites', [
            ['locale' => 'nl', 'fallback_locale' => null],
            ['locale' => 'en', 'fallback_locale' => null],
        ]);

        app()->setLocale('en');
        $this->assertNull($this->model->title);
    }

    public function test_it_returns_null_when_requested_locale_is_not_in_site_locales()
    {
        ChiefSites::clearCache();
        config()->set('chief.sites', [
            ['locale' => 'nl', 'fallback_locale' => null],
            ['locale' => 'en', 'fallback_locale' => null],
        ]);

        $this->model->setDynamic('title', 'Nederlandse titel', 'nl');
        $this->model->setDynamic('title', 'English title', 'en');

        app()->setLocale('fr'); // 'fr' is not in model site locales ['nl', 'en']
        $this->assertNull($this->model->title);
    }

    public function test_it_falls_back_through_multiple_levels()
    {
        ChiefSites::clearCache();
        config()->set('chief.sites', [
            ['locale' => 'fr', 'fallback_locale' => 'en'],
            ['locale' => 'en', 'fallback_locale' => 'nl'],
            ['locale' => 'nl', 'fallback_locale' => null],
        ]);

        $this->model->setSiteLocales(['nl', 'en', 'fr']);
        $this->model->save();

        $this->model->setDynamic('title', 'Nederlandse titel', 'nl');

        app()->setLocale('fr');
        $this->assertEquals('Nederlandse titel', $this->model->title);
    }
}
