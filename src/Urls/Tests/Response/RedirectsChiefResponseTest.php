<?php

namespace Thinktomorrow\Chief\Urls\Tests\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\AddRedirect;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\CreateRedirectTo;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\ChiefResponse;

class RedirectsChiefResponseTest extends ChiefTestCase
{
    private UrlApplication $application;

    private RedirectApplication $redirectApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->application = app(UrlApplication::class);
        $this->redirectApplication = app(RedirectApplication::class);

        ArticlePage::migrateUp();
    }

    public function test_it_can_redirect_url(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::archived]);
        $recordId = $this->application->create(new CreateUrl($model->modelReference(), 'nl', 'foo/bar', 'online'));

        $this->redirectApplication->createRedirectTo(new CreateRedirectTo($recordId, 'redirected-url'));

        $response = ChiefResponse::fromSlug('redirected-url');

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertTrue($response->isRedirect('http://localhost/foo/bar'));
    }

    public function test_it_redirects_an_archived_url()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::archived]);
        $model2 = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::published]);

        $recordId = $this->application->create(new CreateUrl($model->modelReference(), 'nl', 'foo/bar', 'online'));
        $record2Id = $this->application->create(new CreateUrl($model2->modelReference(), 'nl', 'foo/bar/new', 'online'));

        $this->redirectApplication->addRedirect(new AddRedirect($recordId, $record2Id));

        $response = ChiefResponse::fromSlug('nl-base/foo/bar');

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertTrue($response->isRedirect('http://localhost/nl-base/foo/bar/new'));
    }
}
