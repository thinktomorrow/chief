<?php

namespace Thinktomorrow\Chief\Tests\Feature\States;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Management\Application\DeleteManagedModel;
use Thinktomorrow\Chief\Management\Application\ArchiveManagedModel;

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
        $this->asAdmin()
            ->post(route('chief.back.assistants.publish', ['singles', $this->page->id]));

        $this->assertEquals(PageState::PUBLISHED, $this->page->fresh()->state());
    }

    /** @test */
    public function it_cannot_publish_an_archived_page()
    {
        app(ArchiveManagedModel::class)->handle($this->page);

        $this->asAdmin()
            ->post(route('chief.back.assistants.publish', ['singles', $this->page->id]));

        $this->assertEquals(PageState::ARCHIVED, $this->page->fresh()->state());
    }
}
