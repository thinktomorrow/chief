<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Site\Urls\ChiefResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;

class ChiefResponseTest extends ChiefTestCase
{
    protected $keepOriginalSiteRoute = true;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    function it_returns_a_laravel_response()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::PUBLISHED]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $request = new Request([],[],[],[],[],[
            'REQUEST_URI' => '/foo/bar',
        ]);

       $response = ChiefResponse::fromSlug('foo/bar');

       $this->assertInstanceOf(Response::class, $response);
       $this->assertEquals('article-content', $response->getContent());
    }

    /** @test */
    function if_it_cannot_find_a_matching_url_record_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        $request = new Request([],[],[],[],[],[
            'REQUEST_URI' => 'xxx',
        ]);

        ChiefResponse::fromSlug('xxx');
    }

    /** @test */
    function if_it_cannot_find_a_matching_model_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => '', 'model_id' => 0]);

        $request = new Request([],[],[],[],[],[
            'REQUEST_URI' => 'foo/bar',
        ]);

        ChiefResponse::fromSlug('foo/bar');
    }

    /** @test */
    function if_the_page_is_not_published_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::DRAFT]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $request = new Request([],[],[],[],[],[
            'REQUEST_URI' => 'foo/bar',
        ]);

        ChiefResponse::fromSlug('foo/bar');
    }

    /** @test */
    function if_the_page_is_not_published_admin_can_view_with_preview_mode()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::DRAFT]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $response = $this->asAdmin()->get('foo/bar');
        $response->assertSuccessful();

        // Assert that preview mode is indeed active
        $this->assertTrue(PreviewMode::fromRequest()->check());
    }

    /** @test */
    function if_the_page_is_not_published_admin_cannot_view_without_preview_mode()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::DRAFT]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        PreviewMode::toggle();
        $response = $this->asAdmin()->get('foo/bar');
        $response->assertStatus(404);

        // Assert that preview mode is indeed active
        $this->assertFalse(PreviewMode::fromRequest()->check());
    }

    /** @test */
    function it_can_find_a_model_for_a_localized_request()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::PUBLISHED]);
        $record = UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $response = ChiefResponse::fromSlug('foo/bar', 'en');
        $this->assertEquals('article-content', $response->getContent());
    }

    /** @test */
    function it_cannot_respond_when_url_does_not_exist_for_given_locale()
    {
        $this->expectException(NotFoundHttpException::class);

        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::PUBLISHED]);
        UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $response = ChiefResponse::fromSlug('foo/bar', 'nl');
    }

    /** @test */
    function it_can_redirect_an_archived_url()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::ARCHIVED]);
        $model2 = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::PUBLISHED]);

        $record = UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);
        $record2 = UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar/new', 'model_type' => $model2->getMorphClass(), 'model_id' => $model2->id]);

        $record->redirectTo($record2);

        $response = ChiefResponse::fromSlug('foo/bar', 'en');

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertTrue($response->isRedirect('http://localhost/foo/bar/new'));
    }
}
