<?php

namespace Thinktomorrow\Chief\Sites\Tests\Unit;

use InvalidArgumentException;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ChiefSitesUnitTest extends ChiefTestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_it_creates_from_array()
    {
        $site = $this->dummySiteConfig(['locale' => 'nl']);
        $sites = ChiefSites::fromArray([$site]);

        $this->assertCount(1, $sites->get());
    }

    public function test_it_filters_by_locales()
    {
        $site1 = $this->dummySiteConfig(['locale' => 'nl']);
        $site2 = $this->dummySiteConfig(['locale' => 'fr']);
        $sites = ChiefSites::fromArray([$site1, $site2]);

        $filtered = $sites->filterByLocales(['nl']);
        $this->assertCount(1, $filtered->get());
        $this->assertEquals('nl', $filtered->get()[0]->locale);
    }

    public function test_it_rejects_by_locales()
    {
        $site1 = $this->dummySiteConfig(['locale' => 'nl']);
        $site2 = $this->dummySiteConfig(['locale' => 'fr']);
        $sites = ChiefSites::fromArray([$site1, $site2]);

        $rejected = $sites->rejectByLocales(['fr']);
        $this->assertCount(1, $rejected->get());
        $this->assertEquals('nl', $rejected->get()[0]->locale);
    }

    public function test_it_returns_primary_locale()
    {
        $site1 = $this->dummySiteConfig(['locale' => 'nl', 'isPrimary' => true]);
        $site2 = $this->dummySiteConfig(['locale' => 'fr']);
        $sites = ChiefSites::fromArray([$site1, $site2]);

        $this->assertEquals('nl', $sites->getPrimaryLocale());
    }

    public function test_it_defaults_to_first_as_primary_if_none_marked()
    {
        $site1 = $this->dummySiteConfig(['locale' => 'nl']);
        $site2 = $this->dummySiteConfig(['locale' => 'fr']);
        $sites = ChiefSites::fromArray([$site1, $site2]);

        $this->assertEquals('nl', $sites->getPrimaryLocale());
    }

    public function test_it_fails_on_duplicate_locales()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Site locales should be unique.');

        ChiefSites::fromArray([
            $this->dummySiteConfig(['locale' => 'nl']),
            $this->dummySiteConfig(['locale' => 'nl']),
        ]);
    }

    public function test_it_fails_when_no_sites_are_added()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one site should be provided.');

        config()->set('chief.sites', []);
        ChiefSites::fromConfig();
    }

    public function test_it_can_find_site_by_locale()
    {
        $site1 = $this->dummySiteConfig(['locale' => 'nl']);
        $sites = ChiefSites::fromArray([$site1]);

        $found = $sites->find('nl');
        $this->assertEquals('nl', $found->locale);
    }

    public function test_find_throws_when_not_found()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Site with id [de] not found');

        $site1 = $this->dummySiteConfig(['locale' => 'nl']);
        $sites = ChiefSites::fromArray([$site1]);

        $sites->find('de');
    }

    public function test_static_cache_works()
    {
        config(['chief.sites' => [
            $this->dummySiteConfig(['locale' => 'nl']),
        ]]);

        $first = ChiefSites::all();
        $second = ChiefSites::all();

        $this->assertSame($first, $second);
    }

    public function test_verify_locale()
    {
        config(['chief.sites' => [
            $this->dummySiteConfig(['locale' => 'nl']),
            $this->dummySiteConfig(['locale' => 'fr']),
        ]]);

        $this->assertTrue(ChiefSites::verify('nl'));
        $this->assertFalse(ChiefSites::verify('de'));
    }

    public function test_fallback_locales()
    {
        config(['chief.sites' => [
            $this->dummySiteConfig(['locale' => 'nl', 'fallback_locale' => 'en']),
            $this->dummySiteConfig(['locale' => 'fr', 'fallback_locale' => 'nl']),
        ]]);

        $expected = ['nl' => 'en', 'fr' => 'nl'];

        $this->assertEquals($expected, ChiefSites::fallbackLocales());
    }

    public function test_asset_fallback_locales()
    {
        config(['chief.sites' => [
            $this->dummySiteConfig(['locale' => 'nl', 'asset_fallback_locale' => 'en']),
            $this->dummySiteConfig(['locale' => 'fr', 'asset_fallback_locale' => 'fr']),
        ]]);

        $expected = ['nl' => 'en', 'fr' => 'fr'];

        $this->assertEquals($expected, ChiefSites::assetFallbackLocales());
    }

    public function test_it_counts_and_iterates()
    {
        $site1 = $this->dummySiteConfig(['locale' => 'nl']);
        $site2 = $this->dummySiteConfig(['locale' => 'fr']);
        $sites = ChiefSites::fromArray([$site1, $site2]);

        $this->assertCount(2, $sites);

        $locales = [];
        foreach ($sites as $site) {
            $locales[] = $site->locale;
        }

        $this->assertEquals(['nl', 'fr'], $locales);
    }

    private function dummySiteConfig(array $overrides = []): array
    {
        return array_merge([
            'locale' => 'nl',
            'name' => 'Dutch',
            'shortName' => 'NL',
            'code' => 'nl',
            'adjective' => 'Dutch',
            'isPrimary' => false,
            'isActive' => true,
            'fallbackLocale' => 'en',
            'assetFallbackLocale' => 'en',
        ], $overrides);
    }
}
