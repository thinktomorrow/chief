<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Concerns\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;

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
        $page = Page::create(['slug:nl' => 'bar']);

        $this->assertInstanceOf(ProvidesUrl::class, $page);
        $this->assertEquals(url('/bar'), $page->url());
    }

    /** @test */
    function a_page_can_provide_an_url_with_a_segmented_slug()
    {
        $page = Page::create(['slug:nl' => 'bar/foo']);

        $this->assertEquals(url('/bar/foo'), $page->url());
    }

    /** @test */
    function a_page_without_slug_has_no_url_and_gives_empty_string()
    {
        $page = Page::create([]);

        $this->assertEquals('', $page->url());
    }
}
