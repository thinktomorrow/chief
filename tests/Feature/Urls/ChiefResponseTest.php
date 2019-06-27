<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\ChiefResponse;
use Thinktomorrow\Chief\Urls\UrlRecord;

class ChiefResponseTest extends TestCase
{
    /** @test */
    function it_returns_response_by_request()
    {
        $model = ProductPageFake::create();
        $model->publish();
        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->morphKey(), 'model_id' => $model->id]);

        $request = new Request([],[],[],[],[],[
            'REQUEST_URI' => '/foo/bar',
        ]);

       $response = ChiefResponse::fromSlug('foo/bar');

       $this->assertInstanceOf(Response::class, $response);
       $this->assertInstanceOf(ChiefResponse::class, $response);
       $this->assertEquals('product-page-fake-content', $response->getContent());
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

        $model = ProductPageFake::create();
        $model->draft();

        $record = UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $model->morphKey(), 'model_id' => $model->id]);

        $request = new Request([],[],[],[],[],[
            'REQUEST_URI' => 'foo/bar',
        ]);

        ChiefResponse::fromSlug('foo/bar');
    }

    /** @test */
    function it_can_find_a_model_for_a_localized_request()
    {
        $model = ProductPageFake::create();
        $model->publish();
        $record = UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => $model->morphKey(), 'model_id' => $model->id]);

        $response = ChiefResponse::fromSlug('foo/bar', 'en');
        $this->assertEquals('product-page-fake-content', $response->getContent());
    }

    /** @test */
    function it_cannot_respond_when_url_does_not_exist_for_given_locale()
    {
        $this->expectException(NotFoundHttpException::class);

        $model = ProductPageFake::create();
        $model->publish();
        UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => $model->morphKey(), 'model_id' => $model->id]);

        $response = ChiefResponse::fromSlug('foo/bar', 'nl');
    }

    /** @test */
    function it_can_redirect_an_archived_url()
    {
        Route::get('{slug}', function () { })->name('pages.show');

        $model = ProductPageFake::create();
        $model2 = ProductPageFake::create();
        $model->archive();

        $record = UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar', 'model_type' => $model->morphKey(), 'model_id' => $model->id]);
        $record2 = UrlRecord::create(['locale' => 'en', 'slug' => 'foo/bar/new', 'model_type' => $model2->morphKey(), 'model_id' => $model2->id]);

        $record->redirectTo($record2);

        $response = ChiefResponse::fromSlug('foo/bar', 'en');

        $this->assertEquals(301, $response->getStatusCode());
        $this->assertTrue($response->isRedirect('http://localhost/foo/bar/new'));
    }
}
