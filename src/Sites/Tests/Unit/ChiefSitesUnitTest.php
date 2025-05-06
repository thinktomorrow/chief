<?php

namespace Thinktomorrow\Chief\Sites\Tests\Unit;

class ChiefSitesUnitTest extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();
        ChiefSites::clearCache();
    }

    public function test_it_creates_from_array()
    {
        $site = $this->makeChiefSite(['locale' => 'nl']);
        $sites = new \ReflectionClass(ChiefSites::class);
        $method = $sites->getMethod('fromArray');
        $method->setAccessible(true);

        $instance = $method->invoke(null, [$site->toArray()]);
        $this->assertInstanceOf(ChiefSites::class, $instance);
        $this->assertCount(1, $instance->get());
    }

    public function test_it_filters_by_locales()
    {
        $site1 = $this->makeChiefSite(['locale' => 'nl']);
        $site2 = $this->makeChiefSite(['locale' => 'fr']);
        $sites = new ChiefSites($site1, $site2);

        $filtered = $sites->filterByLocales(['nl']);
        $this->assertCount(1, $filtered->get());
        $this->assertEquals('nl', $filtered->get()[0]->locale);
    }

    public function test_it_rejects_by_locales()
    {
        $site1 = $this->makeChiefSite(['locale' => 'nl']);
        $site2 = $this->makeChiefSite(['locale' => 'fr']);
        $sites = new ChiefSites($site1, $site2);

        $rejected = $sites->rejectByLocales(['fr']);
        $this->assertCount(1, $rejected->get());
        $this->assertEquals('nl', $rejected->get()[0]->locale);
    }

    public function test_it_returns_primary_locale()
    {
        $site1 = $this->makeChiefSite(['locale' => 'nl', 'isPrimary' => true]);
        $site2 = $this->makeChiefSite(['locale' => 'fr']);
        $sites = new ChiefSites($site1, $site2);

        $this->assertEquals('nl', $sites->getPrimaryLocale());
    }

    public function test_it_defaults_to_first_as_primary_if_none_marked()
    {
        $site1 = $this->makeChiefSite(['locale' => 'nl']);
        $site2 = $this->makeChiefSite(['locale' => 'fr']);
        $sites = new ChiefSites($site1, $site2);

        $this->assertEquals('nl', $sites->getPrimaryLocale());
    }

    public function test_it_fails_on_duplicate_locales()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Site locales should be unique.');

        new ChiefSites(
            makeChiefSite(['locale' => 'nl']),
            makeChiefSite(['locale' => 'nl'])
        );
    }

    public function test_it_fails_when_no_sites_are_added()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('At least one site should be provided.');

        config(['chief.sites' => []]);
        ChiefSites::fromConfig();
    }

    public function test_it_can_find_site_by_locale()
    {
        $site1 = $this->makeChiefSite(['locale' => 'nl']);
        $sites = new ChiefSites($site1);

        $found = $sites->find('nl');
        $this->assertEquals('nl', $found->locale);
    }

    public function test_find_throws_when_not_found()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Site with id [de] not found');

        $site1 = $this->makeChiefSite(['locale' => 'nl']);
        $sites = new ChiefSites($site1);

        $sites->find('de');
    }

    public function test_static_cache_works()
    {
        config(['chief.sites' => [
            makeChiefSite(['locale' => 'nl'])->toArray(),
        ]]);

        $first = ChiefSites::all();
        $second = ChiefSites::all();

        $this->assertSame($first, $second);
    }

    public function test_verify_locale()
    {
        config(['chief.sites' => [
            makeChiefSite(['locale' => 'nl'])->toArray(),
            makeChiefSite(['locale' => 'fr'])->toArray(),
        ]]);

        $this->assertTrue(ChiefSites::verify('nl'));
        $this->assertFalse(ChiefSites::verify('de'));
    }

    public function test_fallback_locales()
    {
        config(['chief.sites' => [
            makeChiefSite(['locale' => 'nl', 'fallbackLocale' => 'en'])->toArray(),
            makeChiefSite(['locale' => 'fr', 'fallbackLocale' => 'nl'])->toArray(),
        ]]);

        $expected = ['nl' => 'en', 'fr' => 'nl'];

        $this->assertEquals($expected, ChiefSites::fallbackLocales());
    }

    public function test_asset_fallback_locales()
    {
        config(['chief.sites' => [
            makeChiefSite(['locale' => 'nl', 'assetFallbackLocale' => 'en'])->toArray(),
            makeChiefSite(['locale' => 'fr', 'assetFallbackLocale' => 'fr'])->toArray(),
        ]]);

        $expected = ['nl' => 'en', 'fr' => 'fr'];

        $this->assertEquals($expected, ChiefSites::assetFallbackLocales());
    }

    public function test_it_counts_and_iterates()
    {
        $site1 = $this->makeChiefSite(['locale' => 'nl']);
        $site2 = $this->makeChiefSite(['locale' => 'fr']);
        $sites = new ChiefSites($site1, $site2);

        $this->assertCount(2, $sites);

        $locales = [];
        foreach ($sites as $site) {
            $locales[] = $site->locale;
        }

        $this->assertEquals(['nl', 'fr'], $locales);
    }

    private function makeChiefSite(array $overrides = []): ChiefSite
    {
        return ChiefSite::fromArray(array_merge([
            'locale' => 'nl',
            'name' => 'Dutch',
            'shortName' => 'NL',
            'code' => 'nl',
            'adjective' => 'Dutch',
            'isPrimary' => false,
            'isActive' => true,
            'fallbackLocale' => 'en',
            'assetFallbackLocale' => 'en',
        ], $overrides));
    }
}
