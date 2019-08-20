<?php

namespace Thinktomorrow\Chief\Tests\Feature\States;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Management\Application\DeleteManagedModel;
use Thinktomorrow\Chief\Management\Application\ArchiveManagedModel;

class UnarchivePageTest extends TestCase
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
    public function it_can_unarchive_a_page()
    {
        app(ArchiveManagedModel::class)->handle($this->page);

        $this->asAdmin()
            ->post(route('chief.back.assistants.unarchive', ['singles', $this->page->id]));

        $this->assertEquals(PageState::DRAFT, $this->page->fresh()->state());
    }

    /** @test */
    public function it_cannot_unarchive_a_deleted_page()
    {
        app(ArchiveManagedModel::class)->handle($this->page);
        app(DeleteManagedModel::class)->handle($this->page);

        $this->asAdmin()
            ->post(route('chief.back.assistants.unarchive', ['singles', $this->page->id]));

        $this->assertEquals(PageState::DELETED, $this->page->fresh()->state());
        $this->assertTrue($this->page->fresh()->trashed());
    }
}
