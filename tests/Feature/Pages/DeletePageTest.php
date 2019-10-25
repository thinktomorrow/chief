<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\States\PageState;
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

        $this->page = factory(Page::class)->create(['current_state' => PageState::DRAFT]);
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
    public function it_can_delete_all_page_urls_and_redirects()
    {
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $this->page->morphKey(), 'model_id' => $this->page->id]);
        $record2 = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar/new', 'model_type' => $this->page->morphKey(), 'model_id' => $this->page->id]);
        $record->redirectTo($record2);

        $this->assertCount(2, UrlRecord::all());

        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['singles', $this->page->id]), [
                'deleteconfirmation' => 'DELETE'
            ]);

        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::onlyTrashed()->get());
        $this->assertCount(0, UrlRecord::all());
    }

    /** @test */
    public function url_redirecting_to_other_page_is_kept_alive()
    {
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $this->page->morphKey(), 'model_id' => $this->page->id]);
        $record2 = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar/new', 'model_type' => $this->page->morphKey(), 'model_id' => $this->page->id]);
        $record->redirectTo($record2);

        $this->assertCount(2, UrlRecord::all());

        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['singles', $this->page->id]), [
                'deleteconfirmation' => 'DELETE'
            ]);

        $this->assertCount(0, Page::all());
        $this->assertCount(1, Page::onlyTrashed()->get());
        $this->assertCount(0, UrlRecord::all());
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
        $this->page->changeState(PageState::PUBLISHED);
        $this->page->save();

        $this->asAdmin()
            ->delete(route('chief.back.managers.delete', ['singles', $this->page->id]), [
                'deleteconfirmation' => 'DELETE'
            ]);

        $this->assertCount(1, Page::all());
    }
}
