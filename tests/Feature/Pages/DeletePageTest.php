<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageManager;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;

class DeletePageTest extends TestCase
{
    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();

        $this->page = factory(Page::class)->create(['published' => false]);
        app(Register::class)->register(PageManager::class, Single::class);
    }

    /** @test */
    public function it_can_delete_a_page()
    {
        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['singles', $this->page->id]), [
                'deleteconfirmation' => 'DELETE'
            ]);

        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::onlyTrashed()->get());
    }

    /** @test */
    public function page_is_not_deleted_with_invalid_confirmation_string()
    {
        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['singles', $this->page->id]), [
                'deleteconfirmation' => 'FAKE'
            ]);

        $this->assertCount(1, Page::all());
        $this->assertCount(0, Page::onlyTrashed()->get());
    }

    /** @test */
    public function a_published_page_cannot_be_deleted()
    {
        $this->page->publish();

        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['singles', $this->page->id]), [
                'deleteconfirmation' => 'DELETE'
            ]);

        $this->assertCount(1, Page::all());
    }
}
