<?php

namespace Thinktomorrow\Chief\Urls\Tests\Response;

use Illuminate\Http\Response;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\ChiefResponse;

class HandlesChiefResponseTest extends ChiefTestCase
{
    private UrlApplication $application;

    private Visitable $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->application = app(UrlApplication::class);

        $this->model = $this->setUpAndCreateArticle(['current_state' => PageState::published->value]);
    }

    public function test_it_returns_a_laravel_response()
    {
        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('nl-base/foo/bar');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n", $response->getContent());
    }

    public function test_it_can_find_a_model_for_a_localized_request()
    {
        $this->application->create(new CreateUrl($this->model->modelReference(), 'en', 'foo/bar', 'online'));

        $response = ChiefResponse::fromSlug('en-base/foo/bar', 'en');
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n", $response->getContent());
    }
}
