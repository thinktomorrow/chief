<?php

namespace Thinktomorrow\Chief\Urls\Tests\Response;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\App\Exceptions\ChiefExceptionHandler;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Shared\ModelReferences\CannotInstantiateModelReference;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\ChiefResponse;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;
use Throwable;

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
        config()->set('chief.strict', true);

        UrlRecord::create(['site' => 'nl', 'slug' => 'foo/bar', 'model_type' => 'xxx', 'model_id' => 0, 'status' => 'online']);

        $this->expectException(CannotInstantiateModelReference::class);

        $response = ChiefResponse::fromSlug('foo/bar');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_if_the_url_points_to_a_non_existing_model_id_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        UrlRecord::create([
            'site' => 'nl',
            'slug' => 'foo/missing-model',
            'model_type' => $this->model->getMorphClass(),
            'model_id' => 999999,
            'status' => 'online',
        ]);

        $response = ChiefResponse::fromSlug('foo/missing-model');
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function test_it_reports_missing_model_reference_before_responding_with_404(): void
    {
        $reportingHandler = new class extends ChiefExceptionHandler
        {
            public array $reported = [];

            public function __construct() {}

            public function report(Throwable $e): void
            {
                $this->reported[] = $e;
            }
        };

        $this->app->instance(ExceptionHandler::class, $reportingHandler);

        UrlRecord::create([
            'site' => 'nl',
            'slug' => 'foo/missing-model-reported',
            'model_type' => $this->model->getMorphClass(),
            'model_id' => 999999,
            'status' => 'online',
        ]);

        $this->expectException(NotFoundHttpException::class);

        try {
            ChiefResponse::fromSlug('foo/missing-model-reported');
        } finally {
            $this->assertCount(1, $reportingHandler->reported);
            $this->assertInstanceOf(CannotInstantiateModelReference::class, $reportingHandler->reported[0]);
        }
    }

    public function test_if_the_url_points_to_a_non_existing_model_id_it_throws_exception_in_strict_mode()
    {
        config()->set('chief.strict', true);

        UrlRecord::create([
            'site' => 'nl',
            'slug' => 'foo/missing-model-strict',
            'model_type' => $this->model->getMorphClass(),
            'model_id' => 999999,
            'status' => 'online',
        ]);

        $this->expectException(CannotInstantiateModelReference::class);

        ChiefResponse::fromSlug('foo/missing-model-strict');
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
