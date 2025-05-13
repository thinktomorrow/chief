<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Illuminate\Support\Facades\Event;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\DeleteUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class DeletingUrlTest extends ChiefTestCase
{
    private UrlRepository $repository;

    private UrlApplication $application;

    private Visitable $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(UrlRepository::class);
        $this->application = app(UrlApplication::class);

        $this->model = $this->setUpAndCreateArticle();
    }

    public function test_it_deletes_url_and_dispatches_event()
    {
        Event::fake();

        $existingId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'my-slug',
            'status' => LinkStatus::online->value,
        ]);

        $this->application->delete(new DeleteUrl($existingId));

        $this->assertDatabaseMissing('chief_urls', ['id' => $existingId]);

        Event::assertDispatched(\Thinktomorrow\Chief\Urls\Events\UrlDeleted::class);
    }

    public function test_it_can_protect_against_deleting_homepage_url(): void
    {
        $this->expectException(\Thinktomorrow\Chief\Urls\Exceptions\CannotDeleteHomepageSlug::class);

        $existingId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => '/',
            'status' => LinkStatus::online->value,
        ]);

        $this->application->safeDelete(new DeleteUrl($existingId));

        $this->assertDatabaseHas('chief_urls', ['id' => $existingId]);
    }
}
