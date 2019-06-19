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
    public function it_cant_archive_a_page_without_permission()
    {
        $this->asAdminWithoutRole()
            ->post(route('chief.back.assistants.archive', ['singles', $this->page->id]));

        $this->assertCount(0, Page::archived()->get());
    }

    /** @test */
    public function it_can_archive_a_page()
    {
        $this->asDeveloper()
            ->post(route('chief.back.assistants.archive', ['singles', $this->page->id]));

        // Archived page is not included in default retrieval
        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withArchived()->get());
    }

    /** @test */
    public function a_published_page_can_be_archived()
    {
        $this->page->publish();

        $this->asDeveloper()
            ->post(route('chief.back.assistants.archive', ['singles', $this->page->id]));

        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withArchived()->get());
    }
}
