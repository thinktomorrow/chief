<?php

namespace Thinktomorrow\Chief\Tests\Feature\States\PageState;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;

class PublishModelTest extends TestCase
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
    public function it_can_publish_a_page()
    {
        $this->disableExceptionHandling();
        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        $this->asAdmin()->get($pageManager->route('edit'));
        $response = $this->asAdmin()->post($pageManager->assistant('publish')->route('publish'));
        $response->assertStatus(302)->assertRedirect($pageManager->route('edit'));

        $this->assertEquals(PageState::PUBLISHED, $this->page->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_can_unpublish_a_published_page()
    {
        $this->page->changeStateOf(PageState::KEY, PageState::PUBLISHED);
        $this->page->save();

        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        $this->asAdmin()->get($pageManager->route('edit'));
        $response = $this->asAdmin()->post($pageManager->assistant('publish')->route('unpublish'));
        $response->assertStatus(302)->assertRedirect($pageManager->route('edit'));

        $this->assertEquals(PageState::DRAFT, $this->page->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_cannot_publish_an_archived_page()
    {
        $this->page->changeStateOf(PageState::KEY, PageState::ARCHIVED);
        $this->page->save();

        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        $this->asAdmin()->post($pageManager->assistant('publish')->route('publish'));

        $this->assertEquals(PageState::ARCHIVED, $this->page->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_cannot_unpublish_an_archived_page()
    {
        $this->page->changeStateOf(PageState::KEY, PageState::ARCHIVED);
        $this->page->save();

        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        $this->asAdmin()->post($pageManager->assistant('publish')->route('unpublish'));

        $this->assertEquals(PageState::ARCHIVED, $this->page->fresh()->stateOf(PageState::KEY));
    }
}
