<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\CreateRedirectFromSlugs;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Exceptions\RedirectUrlAlreadyExists;
use Thinktomorrow\Chief\Urls\Exceptions\UrlRecordNotFound;

final class CreateRedirectsFromSlugTest extends ChiefTestCase
{
    private RedirectApplication $redirectApplication;

    private UrlRepository $repository;

    private Visitable $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->redirectApplication = app(RedirectApplication::class);
        $this->repository = app(UrlRepository::class);

        $this->model = $this->setUpAndCreateArticle(['title' => 'foobar', 'current_state' => PageState::published]);
        $this->updateLinks($this->model, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $this->assertNull($this->repository->findRecentRedirectByModel($this->model->modelReference(), 'nl'));
    }

    public function test_it_can_add_redirect()
    {
        $this->redirectApplication->createRedirectFromSlugs(new CreateRedirectFromSlugs('nl', 'foobar-redirect-nl', 'foobar-nl'));

        $this->assertEquals('foobar-redirect-nl', $this->repository->findRecentRedirectByModel($this->model->modelReference(), 'nl')->slug);
    }

    public function test_it_can_add_redirect_with_slash()
    {
        $this->redirectApplication->createRedirectFromSlugs(new CreateRedirectFromSlugs('nl', '/foobar-redirect-nl', '/foobar-nl'));
        $this->assertEquals('foobar-redirect-nl', $this->repository->findRecentRedirectByModel($this->model->modelReference(), 'nl')->slug);

        $this->redirectApplication->createRedirectFromSlugs(new CreateRedirectFromSlugs('en', '/foobar-redirect-en', '/foobar-en'));
        $this->assertEquals('foobar-redirect-en', $this->repository->findRecentRedirectByModel($this->model->modelReference(), 'en')->slug);
    }

    public function test_redirect_is_stripped_from_host()
    {
        $this->redirectApplication->createRedirectFromSlugs(new CreateRedirectFromSlugs('nl', 'http://other.com/foobar-redirect-nl', 'foobar-nl'));
        $this->assertEquals('foobar-redirect-nl', $this->repository->findRecentRedirectByModel($this->model->modelReference(), 'nl')->slug);
    }

    public function test_it_should_not_import_redirect_when_url_already_exists_ignoring_slash()
    {
        $article2 = ArticlePage::create(['title' => 'baz', 'current_state' => PageState::published]);
        $this->updateLinks($article2, ['nl' => 'baz-nl', 'en' => 'baz-en']);

        $this->expectException(RedirectUrlAlreadyExists::class);
        $this->redirectApplication->createRedirectFromSlugs(new CreateRedirectFromSlugs('nl', 'baz-nl', '/foobar-nl'));

        $this->assertNull($this->repository->findRecentRedirectByModel($this->model->modelReference(), 'nl'));
    }

    public function test_it_should_not_import_redirect_when_url_already_exists()
    {
        $article2 = ArticlePage::create(['title' => 'baz', 'current_state' => PageState::published]);
        $this->updateLinks($article2, ['nl' => 'baz-nl', 'en' => 'baz-en']);

        $this->expectException(RedirectUrlAlreadyExists::class);
        $this->redirectApplication->createRedirectFromSlugs(new CreateRedirectFromSlugs('nl', '/baz-nl', '/foobar-nl'));

        $this->assertNull($this->repository->findRecentRedirectByModel($this->model->modelReference(), 'nl'));
    }

    public function test_it_cannot_import_redirect_when_target_url_does_not_exist()
    {
        $this->expectException(UrlRecordNotFound::class);

        $this->redirectApplication->createRedirectFromSlugs(new CreateRedirectFromSlugs('nl', '/baz-nl', '/unknown'));

        $this->assertDatabaseMissing('chief_urls', [
            'site' => 'nl',
            'slug' => 'baz-nl',
            'redirect_id' => null,
        ]);
    }
}
