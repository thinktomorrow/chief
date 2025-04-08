<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\AddRedirect;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\CreateRedirectFromSlugs;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\CreateRedirectTo;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Exceptions\RedirectUrlAlreadyExists;
use Thinktomorrow\Chief\Urls\Exceptions\UrlRecordNotFound;

class RedirectApplicationTest extends ChiefTestCase
{
    private UrlRepository $repository;

    private RedirectApplication $application;

    private Visitable $model;

    private UrlApplication $urlApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(UrlRepository::class);
        $this->application = app(RedirectApplication::class);
        $this->urlApplication = app(UrlApplication::class);

        $this->model = $this->setUpAndCreateArticle();
    }

    public function test_it_creates_redirect()
    {
        $targetId = $this->urlApplication->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'my-slug', 'online', null
        ));

        $this->application->createRedirectTo(new CreateRedirectTo(
            $targetId,
            'redirect-slug',
        ));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $targetId,
            'slug' => 'my-slug',
            'redirect_id' => null,
        ]);

        $this->assertDatabaseHas('chief_urls', [
            'slug' => 'redirect-slug',
            'redirect_id' => $targetId,
        ]);
    }

    public function test_it_creates_redirect_from_slugs()
    {
        $targetId = $this->urlApplication->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'my-slug', 'online', null
        ));

        $this->application->createRedirectFromSlugs(new CreateRedirectFromSlugs(
            'nl', 'redirect-slug', 'my-slug',
        ));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $targetId,
            'slug' => 'my-slug',
            'redirect_id' => null,
        ]);

        $this->assertDatabaseHas('chief_urls', [
            'slug' => 'redirect-slug',
            'redirect_id' => $targetId,
        ]);
    }

    public function test_it_cannot_create_redirect_if_target_slug_does_not_exist()
    {
        $this->expectException(UrlRecordNotFound::class);

        $this->application->createRedirectFromSlugs(new CreateRedirectFromSlugs(
            'nl', 'my-slug', 'unknown-slug',
        ));
    }

    public function test_it_cannot_create_redirect_that_already_exists()
    {
        $targetId = $this->urlApplication->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'my-slug', 'online', null
        ));

        $this->expectException(RedirectUrlAlreadyExists::class);

        $this->application->createRedirectTo(new CreateRedirectTo(
            $targetId,
            'my-slug',
        ));
    }

    public function test_it_can_add_redirect_to_existing_urls(): void
    {
        $redirectId = $this->urlApplication->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'redirect-slug', 'online', null
        ));

        $targetId = $this->urlApplication->create(new CreateUrl(
            $this->model->modelReference(), 'nl', 'my-slug', 'online', null
        ));

        $this->application->addRedirect(new AddRedirect($redirectId, $targetId));

        $this->assertDatabaseHas('chief_urls', [
            'id' => $targetId,
            'slug' => 'my-slug',
            'redirect_id' => null,
        ]);

        $this->assertDatabaseHas('chief_urls', [
            'id' => $redirectId,
            'slug' => 'redirect-slug',
            'redirect_id' => $targetId,
        ]);
    }
}
