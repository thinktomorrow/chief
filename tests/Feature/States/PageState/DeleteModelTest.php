<?php

namespace Thinktomorrow\Chief\Tests\Feature\States\PageState;

use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;

class DeleteModelTest extends TestCase
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
    public function it_cannot_delete_a_draft_page()
    {
        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['singles', $this->page->id]));

        $this->assertEquals(PageState::DRAFT, $this->page->fresh()->stateOf(PageState::KEY));
        $this->assertFalse($this->page->fresh()->trashed());
    }

    /** @test */
    public function it_can_delete_an_archived_page()
    {
        $this->page->changeStateOf(PageState::KEY, PageState::ARCHIVED);
        $this->page->save();

        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['singles', $this->page->id]),[
                'deleteconfirmation' => 'DELETE',
            ]);

        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withTrashed()->get());

        $this->assertEquals(PageState::DELETED, $this->page->fresh()->stateOf(PageState::KEY));
        $this->assertTrue($this->page->fresh()->trashed());
    }
}
