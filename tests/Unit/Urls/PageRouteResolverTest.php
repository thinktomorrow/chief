<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Site\Visitable\PageRouteResolver;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class PageRouteResolverTest extends ChiefTestCase
{
    private $article;

    protected function setUp(): void
    {
        parent::setUp();

        $this->article = $this->setupAndCreateArticle();

        app(PageRouteResolver::class)->define(function ($name, $parameters = [], $locale = null) {
            return 'foobar-' . $locale;
        });
    }

    protected function tearDown(): void
    {
        // Reset the page route resolver.
        app(PageRouteResolver::class)->define(null);

        parent::tearDown();
    }

    /** @test */
    public function a_default_route_resolver_can_be_set()
    {
        // Add links so our url method works properly. These slugs will however be overridden by our custom route resolver.
        $this->updateLinks($this->article, ['nl' => 'fake-nl','en' => 'fake-en']);

        $this->assertEquals('foobar-nl', $this->article->url());
        $this->assertEquals('foobar-en', $this->article->url('en'));
    }
}
