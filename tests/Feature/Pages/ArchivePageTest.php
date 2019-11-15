<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;

class ArchivePageTest extends TestCase
{
    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->page = factory(Page::class)->create(['published' => false]);

        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function only_authenticated_can_view_archive_index()
    {
        $response = $this->asAdmin()
            ->get(route('chief.back.assistants.archive-index', ['singles', $this->page->id]));

        $response->assertStatus(200);
        $response->assertViewIs('chief::back.managers.archive.index');
    }


    /** @test */
    public function it_can_archive_a_page()
    {
        $this->asAdmin()
            ->post(route('chief.back.assistants.archive', ['singles', $this->page->id]));

        // Archived page is not included in default retrieval
        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withArchived()->get());
    }

    /** @test */
    public function a_published_page_can_be_archived()
    {
        $this->page->publish();

        $this->asAdmin()
            ->post(route('chief.back.assistants.archive', ['singles', $this->page->id]));

        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withArchived()->get());
    }

    /** @test */
    public function an_archived_page_unarchived_is_put_in_draft()
    {
        $this->page->publish();
        $this->page->archive();

        $this->assertCount(0, Page::all());

        $this->asAdmin()
            ->post(route('chief.back.assistants.unarchive', ['singles', $this->page->id]));

        $this->assertCount(1, Page::all());
        $this->assertTrue(Page::first()->isDraft());

    }
}
