<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;

class DeletePageTest extends TestCase
{
    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->page = factory(Page::class)->create(['published' => false]);
    }

    /** @test */
    public function it_can_delete_a_page()
    {
        $this->asAdmin()
            ->delete(route('chief.back.pages.destroy', $this->page->id), [
                'deleteconfirmation' => 'DELETE'
            ]);

        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::onlyTrashed()->get());
    }

    /** @test */
    public function page_is_not_deleted_with_invalid_confirmation_string()
    {
        $this->asAdmin()
            ->delete(route('chief.back.pages.destroy', $this->page->id), [
                'deleteconfirmation' => 'FAKE'
            ]);

        $this->assertCount(1, Page::all());
        $this->assertCount(0, Page::onlyTrashed()->get());
    }

    /** @test */
    public function a_published_page_is_archived()
    {
        $this->page->publish();

        $this->asAdmin()
            ->delete(route('chief.back.pages.destroy', Page::first()->id),[
                'deleteconfirmation' => 'DELETE'
            ]);

        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::withArchived()->get());
    }
}
