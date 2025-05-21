<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\ReactivateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;

class ReactivatingRedirectTest extends ChiefTestCase
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

    public function test_it_can_reactivate_redirect_url(): void
    {
        $activeId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'active-slug',
            'status' => LinkStatus::online->value,
        ]);

        $redirectId = $this->repository->create($this->model->modelReference(), [
            'site' => 'nl',
            'slug' => 'redirect-slug',
            'redirect_id' => $activeId,
            'status' => LinkStatus::online->value,
        ]);

        $this->application->reactivateUrl(new ReactivateUrl($redirectId));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $redirectId,
            'slug' => 'redirect-slug',
            'redirect_id' => null,
        ]);

        $this->assertDatabaseHas('chief_urls', [
            'id' => $activeId,
            'slug' => 'active-slug',
            'redirect_id' => $redirectId,
        ]);
    }
}
