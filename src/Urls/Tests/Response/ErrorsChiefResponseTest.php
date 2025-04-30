<?php

namespace Thinktomorrow\Chief\Urls\Tests\Response;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Shared\ModelReferences\CannotInstantiateModelReference;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\ChiefResponse;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class ErrorsChiefResponseTest extends ChiefTestCase
{
    private UrlApplication $application;

    private Visitable $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->application = app(UrlApplication::class);

        $this->model = $this->setUpAndCreateArticle(['current_state' => PageState::published->value]);

        config()->set('chief.strict', false);
    }

    public function test_if_it_cannot_find_a_matching_url_record_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        ChiefResponse::fromSlug('xxx');
    }

    public function test_it_cannot_respond_when_url_does_not_exist_for_given_locale()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->application->create(new CreateUrl($this->model->modelReference(), 'en', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('foo/bar', 'nl');

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_if_the_model_reference_is_invalid_it_throws_the_correct_exception()
    {
        UrlRecord::create(['site' => 'nl', 'slug' => 'foo/bar', 'model_type' => 'xxx', 'model_id' => 0, 'status' => 'online']);

        $this->expectException(CannotInstantiateModelReference::class);

        $response = ChiefResponse::fromSlug('foo/bar');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_if_the_page_is_not_published_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->model->current_state = PageState::draft->value;
        $this->model->save();

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('foo/bar');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_if_the_url_is_offline_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'offline'));

        $response = ChiefResponse::fromSlug('foo/bar');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_if_the_page_is_archived_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->model->current_state = PageState::archived->value;
        $this->model->save();

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('foo/bar');
        $this->assertEquals(404, $response->getStatusCode());
    }
}
