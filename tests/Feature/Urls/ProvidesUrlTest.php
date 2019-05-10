<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\UrlRecord;

class ProvidesUrlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::get('{slug}', function () { })->name('pages.show');
    }

    /** @test */
    function a_page_can_provide_an_url()
    {
        $page = Page::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertInstanceOf(ProvidesUrl::class, $page);
        $this->assertEquals(url('/bar'), $page->url('nl'));
    }

    /** @test */
    function when_url_is_not_found_for_locale_empty_string_is_returned()
    {
        $page = Page::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertEquals('', $page->url('fr'));
    }

    /** @test */
    function when_no_locale_is_passed_current_locale_is_used()
    {
        $page = Page::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        app()->setLocale('fr');
        $this->assertEquals('', $page->url());

        app()->setLocale('nl');
        $this->assertEquals(url('/bar'), $page->url());
    }

    /** @test */
    function general_url_is_always_a_fallback_when_localized_url_does_not_exist()
    {
        $page = Page::create();
        UrlRecord::create(['locale' => null, 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        app()->setLocale('fr');
        $this->assertEquals(url('/bar'), $page->url());

        app()->setLocale('nl');
        $this->assertEquals(url('/bar'), $page->url());
    }

    /** @test */
    function a_page_can_provide_an_url_with_a_segmented_slug()
    {
        $this->disableExceptionHandling();
        $page = Page::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar/foo', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertEquals(url('/bar/foo'), $page->url());
    }

    /** @test */
    function a_page_without_url_record_entry_has_no_url_and_gives_empty_string()
    {
        $page = Page::create([]);

        $this->assertEquals('', $page->url());
    }

    /** @test */
    function the_base_url_segment_only_affects_newly_edited_urls()
    {

    }
}
