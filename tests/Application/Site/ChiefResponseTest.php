<?php

namespace Thinktomorrow\Chief\Tests\Application\Site;

use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\Shared\ModelReferences\CannotInstantiateModelReference;
use Thinktomorrow\Chief\Site\Urls\ChiefResponse;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class ChiefResponseTest extends ChiefTestCase
{
    protected $keepOriginalSiteRoute = true;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
    }

    /** @test */
    public function it_returns_a_laravel_response()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::published]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $response = ChiefResponse::fromSlug('foo/bar');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n", $response->getContent());
    }

    /** @test */
    public function if_it_cannot_find_a_matching_url_record_it_throws_404_exception()
    {
        config()->set('chief.strict', false);

        $this->expectException(NotFoundHttpException::class);

        ChiefResponse::fromSlug('xxx');
    }

    /** @test */
    public function if_the_model_reference_is_invalid_it_throws_the_correct_exception()
    {
        config()->set('chief.strict', false);

        $this->expectException(CannotInstantiateModelReference::class);

        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => 'xxx', 'model_id' => 0]);

        ChiefResponse::fromSlug('foo/bar');
    }

    /** @test */
    public function if_the_page_is_not_published_it_throws_404_exception()
    {
        config()->set('chief.strict', false);

        $this->expectException(NotFoundHttpException::class);

        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::draft]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        ChiefResponse::fromSlug('foo/bar');
    }

    /** @test */
    public function if_the_page_is_archived_it_throws_404_exception()
    {
        config()->set('chief.strict', false);

        $this->expectException(NotFoundHttpException::class);

        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::archived]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        ChiefResponse::fromSlug('foo/bar');
    }

    /** @test */
    public function if_the_page_is_not_published_admin_can_view_with_preview_mode()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::draft]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        session()->flash('preview-mode', true);
        $response = $this->asAdmin()->get('foo/bar');
        $response->assertSuccessful();

        // Assert that preview mode is indeed active
        $this->assertTrue(PreviewMode::fromRequest()->check());
    }

    /** @test */
    public function if_the_page_is_not_published_admin_cannot_view_without_preview_mode()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::draft]);
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $response = $this->asAdmin()->get('foo/bar');
        $response->assertStatus(404);

        // Assert that preview mode is indeed active
        $this->assertFalse(PreviewMode::fromRequest()->check());
    }

    /** @test */
    public function it_can_find_a_model_for_a_localized_request()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::published]);
        $record = UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $response = ChiefResponse::fromSlug('foo/bar', 'en');
        $this->assertEquals("THIS IS ARTICLE PAGE VIEW\n", $response->getContent());
    }

    /** @test */
    public function it_cannot_respond_when_url_does_not_exist_for_given_locale()
    {
        config()->set('chief.strict', false);

        $this->expectException(NotFoundHttpException::class);

        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::published]);
        UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $response = ChiefResponse::fromSlug('foo/bar', 'nl');
    }

    /** @test */
    public function it_throws_exception_when_model_does_not_provide_url()
    {
        config()->set('chief.strict', false);

        $this->expectException(\BadMethodCallException::class);

        Quote::migrateUp();
        $model = Quote::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);

        $response = ChiefResponse::fromSlug('foo/bar', 'nl');
    }

    /** @test */
    public function it_can_redirect_an_archived_url()
    {
        $model = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::archived]);
        $model2 = ArticlePage::create(['title' => 'Foobar', 'current_state' => PageState::published]);

        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->getMorphClass(), 'model_id' => $model->id]);
        $record2 = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar/new', 'model_type' => $model2->getMorphClass(), 'model_id' => $model2->id]);

        $record->redirectTo($record2);

        $response = ChiefResponse::fromSlug('foo/bar');

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertTrue($response->isRedirect('http://localhost/foo/bar/new'));
    }
}
