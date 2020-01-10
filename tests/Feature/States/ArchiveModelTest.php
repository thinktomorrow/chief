<?php

namespace Thinktomorrow\Chief\Tests\Feature\States;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\Managers;
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
        $pageManager = app(Managers::class)->findByKey('singles');

        $response = $this->asAdmin()->get($pageManager->assistant('archive')->route('index'));

        $response->assertStatus(200);
        $response->assertViewIs('chief::back.managers.archive.index');
    }

    /** @test */
    public function it_can_archive_a_page()
    {
        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        $this->asAdmin()->post($pageManager->assistant('archive')->route('archive'));

        // Archived page is not included in default retrieval
        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withArchived()->get());

        $this->assertEquals(PageState::ARCHIVED, $this->page->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_can_unarchive_an_archived_page()
    {
        app(ArchiveManagedModel::class)->handle($this->page);

        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);
        $this->asAdmin()->post($pageManager->assistant('archive')->route('unarchive'));

        $this->assertEquals(PageState::DRAFT, $this->page->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_cannot_archive_a_deleted_page()
    {
        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        app(ArchiveManagedModel::class)->handle($this->page);
        app(DeleteManagedModel::class)->handle($this->page);

        $this->asAdmin()->post($pageManager->assistant('archive')->route('archive'));

        $this->assertEquals(PageState::DELETED, $this->page->fresh()->stateOf(PageState::KEY));
        $this->assertTrue($this->page->fresh()->trashed());
    }

    /** @test */
    public function it_cannot_unarchive_a_deleted_page()
    {
        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        app(ArchiveManagedModel::class)->handle($this->page);
        app(DeleteManagedModel::class)->handle($this->page);

        $this->asAdmin()->post($pageManager->assistant('archive')->route('unarchive'));

        $this->assertEquals(PageState::DELETED, $this->page->fresh()->stateOf(PageState::KEY));
        $this->assertTrue($this->page->fresh()->trashed());
    }
}
