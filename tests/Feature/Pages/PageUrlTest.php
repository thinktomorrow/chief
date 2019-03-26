<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;

class PageUrlTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    function a_page_can_have_a_fixed_base_segment()
    {
        $page = ProductPageFake::create(['slug:nl' => 'bar']);

        $this->assertEquals(url('/products/bar'), $page->url() );
    }

    /** @test */
    function a_page_can_have_a_localised_base_segment()
    {
        $page = ProductPageFakeWithLocalisedBaseSegments::create(['slug:nl' => 'bar', 'slug:en' => 'foo']);

        app()->setLocale('nl');
        $this->assertEquals(url('/producten/bar'), $page->url() );

        app()->setLocale('en');
        $this->assertEquals(url('/products/foo'), $page->url() );
    }

    /** @test */
    function when_no_localised_base_segment_is_matched_the_last_is_used_as_fallback()
    {
        $page = ProductPageFakeWithLocalisedBaseSegments::create(['slug:nl' => 'bar', 'slug:fr' => 'foo']);

        app()->setLocale('fr');
        $this->assertEquals(url('/producten/foo'), $page->url() );
    }

    /** @test */
    public function it_can_find_published_by_slug()
    {
        // Via the register, the system knows which are the expected baseUrlSegments...
        app(Register::class)->register('products', PageManager::class, ProductPageFakeWithLocalisedBaseSegments::class);

        $page = ProductPageFakeWithLocalisedBaseSegments::create(['slug:nl' => 'bar', 'slug:fr' => 'foo', 'published' => 1]);

        app()->setLocale('nl');
        $this->assertInstanceOf(Page::class, Page::findPublishedBySlug('/producten/bar'));
        $this->assertInstanceOf(Page::class, Page::findPublishedBySlug('producten/bar'));

        // Without base url segment page can be retrieved as well
        $this->assertInstanceOf(Page::class, Page::findPublishedBySlug('bar'));
    }
}

class ProductPageFakeWithLocalisedBaseSegments extends ProductPageFake
{
    protected static $baseUrlSegment = [
        'en' => 'products',
        'nl' => 'producten',
    ];
}
