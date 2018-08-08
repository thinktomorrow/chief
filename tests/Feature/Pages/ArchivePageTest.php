<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;

class ArchivePageTest extends TestCase
{
    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->page = factory(Page::class)->create(['published' => false]);
    }

    /** @test */
    public function it_can_archive_a_page()
    {
        $this->asAdmin()
            ->put(route('chief.back.pages.archive', $this->page->id));

        // Archived page is not included in default retrieval
        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withArchived()->get());
    }

    /** @test */
    public function a_published_page_is_archived()
    {
        $this->disableExceptionHandling();
        $this->page->publish();

        $this->asAdmin()
            ->put(route('chief.back.pages.archive', $this->page->id));

        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withArchived()->get());
    }
}
