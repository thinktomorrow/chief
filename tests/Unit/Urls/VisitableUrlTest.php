<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class VisitableUrlTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();

        Route::get('{slug}', function () {
        })->name('pages.show');
    }

    public function test_a_page_can_provide_an_url()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertInstanceOf(Visitable::class, $page);
        $this->assertEquals(url('/bar'), $page->url('nl'));
    }

    public function test_when_url_is_not_found_for_locale_empty_string_is_returned()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertEquals('', $page->url('fr'));
    }

    public function test_when_no_locale_is_passed_current_locale_is_used()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        app()->setLocale('fr');
        $this->assertEquals('', $page->url());

        app()->setLocale('nl');
        $this->assertEquals(url('/bar'), $page->url());
    }

    public function test_when_url_does_not_exist_an_empty_string_is_returned()
    {
        config()->set('app.fallback_locale', 'nl');

        $page = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar/nl', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);
        UrlRecord::create(['locale' => 'fr', 'slug' => 'bar/fr', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        app()->setLocale('fr');
        $this->assertEquals(url('/bar/fr'), $page->url());

        app()->setLocale('en');
        $this->assertEquals('', $page->url());
    }

    public function test_a_page_can_provide_an_url_with_a_segmented_slug()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar/foo', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertEquals(url('/bar/foo'), $page->url());
    }

    public function test_a_page_without_url_record_entry_has_no_url_and_gives_empty_string()
    {
        $page = ArticlePage::create([]);

        $this->assertEquals('', $page->url());
    }
}
