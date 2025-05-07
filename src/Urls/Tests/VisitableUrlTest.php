<?php

namespace Thinktomorrow\Chief\Urls\Tests;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class VisitableUrlTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();

        Route::get('{slug}', function () {})->name('pages.show');
    }

    public function test_a_page_can_provide_an_url()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['site' => 'nl', 'status' => 'online', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertInstanceOf(Visitable::class, $page);
        $this->assertEquals(url('/bar'), $page->url('nl'));
    }

    public function test_a_page_can_provide_an_offline_url()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['site' => 'nl', 'status' => 'offline', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertInstanceOf(Visitable::class, $page);
        $this->assertEquals(url('/bar'), $page->rawUrl('nl'));
        $this->assertNull($page->url('nl'));
    }

    public function test_when_url_is_not_found_for_site_empty_string_is_returned()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['site' => 'nl', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertEquals('', $page->url('fr'));
    }

    public function test_when_no_site_is_passed_current_site_is_used()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['site' => 'nl', 'status' => 'online', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        app()->setLocale('fr');
        $this->assertEquals('', $page->url());

        app()->setLocale('nl');
        $this->assertEquals(url('/bar'), $page->url());
    }

    public function test_when_url_does_not_exist_an_empty_string_is_returned()
    {
        config()->set('app.fallback_locale', 'nl');

        $page = ArticlePage::create();
        UrlRecord::create(['site' => 'nl', 'status' => 'online', 'slug' => 'bar/nl', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);
        UrlRecord::create(['site' => 'en', 'status' => 'online', 'slug' => 'bar/en', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        app()->setLocale('en');
        $this->assertEquals(url('/bar/en'), $page->url());

        app()->setLocale('fr');
        $this->assertNull($page->url());
        $this->assertNull($page->rawUrl());
    }

    public function test_a_page_can_provide_an_url_with_a_segmented_slug()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['site' => 'nl', 'status' => 'online', 'slug' => 'bar/foo', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $this->assertEquals(url('/bar/foo'), $page->url());
    }

    public function test_a_page_without_url_record_entry_has_no_url_and_gives_empty_string()
    {
        $page = ArticlePage::create([]);

        $this->assertEquals('', $page->url());
    }
}
