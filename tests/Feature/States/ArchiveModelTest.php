<?php

namespace Thinktomorrow\Chief\Tests\Feature\States;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Management\Application\DeleteManagedModel;
use Thinktomorrow\Chief\Management\Application\ArchiveManagedModel;

class ArchiveModelTest extends TestCase
{
    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->page = ProductPageFake::create()->fresh();

        Route::get('pages/{slug}', function () {
        })->name('pages.show');
    }

    /** @test */
    public function an_admin_can_view_archive_index()
    {
        $this->disableExceptionHandling();
        $response = $this->asAdmin()->get(route('chief.back.assistants.archive-index', ['singles', 'archive']));

        $response->assertStatus(200);
        $response->assertViewIs('chief::back.managers.archive.index');
    }

    /** @test */
    public function it_can_archive_a_page()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()
            ->post(route('chief.back.assistants.archive', ['singles', $this->page->id]));

        // Archived page is not included in default retrieval
        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withArchived()->get());

        $this->assertEquals(PageState::ARCHIVED, $this->page->fresh()->state());
    }

    /** @test */
    public function it_cannot_archive_a_deleted_page()
    {
        app(ArchiveManagedModel::class)->handle($this->page);
        app(DeleteManagedModel::class)->handle($this->page);

        $this->asAdmin()
            ->post(route('chief.back.assistants.archive', ['singles', $this->page->id]));

        $this->assertEquals(PageState::DELETED, $this->page->fresh()->state());
        $this->assertTrue($this->page->fresh()->trashed());
    }
}
