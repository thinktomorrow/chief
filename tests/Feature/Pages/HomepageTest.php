<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Homepage;
use Thinktomorrow\Chief\Pages\NotFoundHomepage;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class HomepageTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function by_default_it_uses_first_published_single_page_as_the_homepage()
    {
        $article  = ArticlePageFake::create();
        $product  = ProductPageFake::create(['published' => 1]);
        $product2 = ProductPageFake::create();

        $this->assertEquals($product->id, Homepage::guess()->id);
    }

    /** @test */
    public function when_no_single_given_it_uses_first_published_page_as_the_homepage()
    {
        $article = ArticlePageFake::create();
        $product = ProductPageFake::create(['published' => 1]);

        $this->assertEquals($product->id, Homepage::guess()->id);
    }

    /** @test */
    public function it_guesses_the_homepage_if_explicitly_set_in_settings()
    {
        $article  = ArticlePageFake::create();
        $product  = ProductPageFake::create(['published' => 1]);
        $product2 = ProductPageFake::create(['published' => 1]);

        $this->app['config']->set('thinktomorrow.chief-settings.homepage', $product2->id);

        $this->assertEquals($product2->id, Homepage::guess()->id);
    }

    /** @test */
    public function if_no_page_can_be_guessed_it_throws_an_exception()
    {
        ArticlePageFake::create();

        $this->expectException(NotFoundHomepage::class);

        Homepage::guess();
    }
}
