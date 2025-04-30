<?php

namespace Thinktomorrow\Chief\Urls\Tests\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\ChiefResponse;

class PreviewsChiefResponseTest extends ChiefTestCase
{
    private UrlApplication $application;

    private Visitable $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->application = app(UrlApplication::class);
        $this->redirectApplication = app(RedirectApplication::class);

        $this->model = $this->setUpAndCreateArticle(['current_state' => PageState::published->value]);
    }

    public function test_if_the_page_is_not_published_admin_can_view_with_preview_mode()
    {
        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'offline'));

        session()->flash('preview-mode');
        $this->asAdmin()->get('xxx');

        $response = ChiefResponse::fromSlug('nl-base/foo/bar');
        $this->assertEquals(200, $response->getStatusCode());

        // Assert that preview mode is indeed active
        $this->assertTrue(PreviewMode::fromRequest()->check());
    }

    public function test_preview_mode_does_not_work_if_not_logged_in()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'offline'));

        session()->flash('preview-mode');

        $response = ChiefResponse::fromSlug('nl-base/foo/bar');
        $this->assertEquals(404, $response->getStatusCode());

        // Assert that preview mode is indeed active
        $this->assertFalse(PreviewMode::fromRequest()->check());
    }

    public function test_if_the_page_is_not_published_admin_cannot_view_without_preview_mode()
    {
        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'offline'));

        $response = $this->asAdmin()->get('nl-base/foo/bar');
        $response->assertStatus(404);

        // Assert that preview mode is indeed active
        $this->assertFalse(PreviewMode::fromRequest()->check());
    }
}
