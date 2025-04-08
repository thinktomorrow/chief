<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\Shared\ModelReferences\CannotInstantiateModelReference;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\AddRedirect;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\ChiefResponse;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class ChiefResponseTest extends ChiefTestCase
{
    protected $keepOriginalSiteRoute = true;

    private UrlApplication $application;

    private Visitable $model;

    private RedirectApplication $redirectApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->application = app(UrlApplication::class);
        $this->redirectApplication = app(RedirectApplication::class);

        $this->model = $this->setUpAndCreateArticle(['current_state' => PageState::published->value]);
    }

    public function test_it_returns_a_laravel_response()
    {
        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('foo/bar');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n", $response->getContent());
    }

    public function test_if_it_cannot_find_a_matching_url_record_it_throws_404_exception()
    {
        config()->set('chief.strict', false);

        $this->expectException(NotFoundHttpException::class);

        ChiefResponse::fromSlug('xxx');
    }

    public function test_if_the_model_reference_is_invalid_it_throws_the_correct_exception()
    {
        config()->set('chief.strict', false);

        UrlRecord::create(['site' => 'nl', 'slug' => 'foo/bar', 'model_type' => 'xxx', 'model_id' => 0, 'status' => 'online']);

        $this->expectException(CannotInstantiateModelReference::class);

        $response = ChiefResponse::fromSlug('foo/bar');
        $this->assertEquals(404, $response->getStatusCode());

    }

    public function test_if_the_page_is_not_published_it_throws_404_exception()
    {
        config()->set('chief.strict', false);

        $this->expectException(NotFoundHttpException::class);

        $this->model->current_state = PageState::draft->value;
        $this->model->save();

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('foo/bar');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_if_the_url_is_offline_it_throws_404_exception()
    {
        config()->set('chief.strict', false);

        $this->expectException(NotFoundHttpException::class);

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'offline'));

        $response = ChiefResponse::fromSlug('foo/bar');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_if_the_page_is_archived_it_throws_404_exception()
    {
        config()->set('chief.strict', false);

        $this->expectException(NotFoundHttpException::class);

        $this->model->current_state = PageState::archived->value;
        $this->model->save();

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('foo/bar');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_if_the_page_is_not_published_admin_can_view_with_preview_mode()
    {
        $this->model->current_state = PageState::draft->value;
        $this->model->save();

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'online'));

        session()->flash('preview-mode', true);
        $response = $this->asAdmin()->get('foo/bar');
        $response->assertSuccessful();

        // Assert that preview mode is indeed active
        $this->assertTrue(PreviewMode::fromRequest()->check());
    }

    public function test_if_the_page_is_not_published_admin_cannot_view_without_preview_mode()
    {
        $this->model->current_state = PageState::draft->value;
        $this->model->save();

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'online'));

        $response = $this->asAdmin()->get('foo/bar');
        $response->assertStatus(404);

        // Assert that preview mode is indeed active
        $this->assertFalse(PreviewMode::fromRequest()->check());
    }

    public function test_it_can_find_a_model_for_a_localized_request()
    {
        $this->application->create(new CreateUrl($this->model->modelReference(), 'en', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('foo/bar', 'en');
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n", $response->getContent());
    }

    public function test_it_cannot_respond_when_url_does_not_exist_for_given_locale()
    {
        config()->set('chief.strict', false);

        $this->expectException(NotFoundHttpException::class);

        $this->application->create(new CreateUrl($this->model->modelReference(), 'en', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('foo/bar', 'nl');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_can_redirect_an_archived_url()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::archived]);
        $model2 = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::published]);

        $recordId = $this->application->create(new CreateUrl($model->modelReference(), 'nl', 'foo/bar', 'online'));
        $record2Id = $this->application->create(new CreateUrl($model2->modelReference(), 'nl', 'foo/bar/new', 'online'));

        $this->redirectApplication->addRedirect(new AddRedirect($recordId, $record2Id));

        $response = ChiefResponse::fromSlug('foo/bar');

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertTrue($response->isRedirect('http://localhost/foo/bar/new'));
    }
}
