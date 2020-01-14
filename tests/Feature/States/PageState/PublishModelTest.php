<?php

namespace Thinktomorrow\Chief\Tests\Feature\States\PageState;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Management\Application\DeleteManagedModel;
use Thinktomorrow\Chief\Management\Application\ArchiveManagedModel;
use Thinktomorrow\Chief\Management\Application\PublishManagedModel;

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
        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        $this->asAdmin()->post($pageManager->assistant('publish')->route('publish'));

        $this->assertEquals(PageState::PUBLISHED, $this->page->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_can_unpublish_a_published_page()
    {
        app(PublishManagedModel::class)->handle($this->page);

        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        $this->asAdmin()->post($pageManager->assistant('publish')->route('unpublish'));

        $this->assertEquals(PageState::DRAFT, $this->page->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_cannot_publish_an_archived_page()
    {
        app(ArchiveManagedModel::class)->handle($this->page);

        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        $this->asAdmin()->post($pageManager->assistant('publish')->route('publish'));

        $this->assertEquals(PageState::ARCHIVED, $this->page->fresh()->stateOf(PageState::KEY));
    }

    /** @test */
    public function it_cannot_unpublish_an_archived_page()
    {
        app(ArchiveManagedModel::class)->handle($this->page);

        $pageManager = app(Managers::class)->findByKey('singles', $this->page->id);

        $this->asAdmin()->post($pageManager->assistant('publish')->route('unpublish'));

        $this->assertEquals(PageState::ARCHIVED, $this->page->fresh()->stateOf(PageState::KEY));
    }
}
