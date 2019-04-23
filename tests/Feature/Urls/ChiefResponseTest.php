<?php

namespace Thinktomorrow\Chief\Tests\Feature\Urls;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Urls\ChiefResponse;
use Thinktomorrow\Chief\Urls\UrlRecord;

class ChiefResponseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    function it_returns_response_by_request()
    {
        $model = ProductPageFake::create();
        $model->publish();
        $record = UrlRecord::create(['slug' => 'foo/bar', 'model_type' => $model->morphKey(), 'model_id' => $model->id]);

        $request = new Request([],[],[],[],[],[
            'REQUEST_URI' => '/foo/bar',
        ]);

       $response = ChiefResponse::fromRequest($request);

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

        ChiefResponse::fromRequest($request);
    }

    /** @test */
    function if_it_cannot_find_a_matching_model_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        UrlRecord::create(['slug' => 'foo/bar', 'model_type' => '', 'model_id' => 0]);

        $request = new Request([],[],[],[],[],[
            'REQUEST_URI' => 'foo/bar',
        ]);

        ChiefResponse::fromRequest($request);
    }

    /** @test */
    function if_the_page_is_not_published_it_throws_404_exception()
    {
        $this->expectException(NotFoundHttpException::class);

        $model = ProductPageFake::create();
        $model->draft();

        $record = UrlRecord::create(['slug' => 'foo/bar', 'model_type' => $model->morphKey(), 'model_id' => $model->id]);

        $request = new Request([],[],[],[],[],[
            'REQUEST_URI' => 'foo/bar',
        ]);

        ChiefResponse::fromRequest($request);
    }

    /** @test */
    function it_can_find_a_model_for_a_localized_request()
    {

    }
}
