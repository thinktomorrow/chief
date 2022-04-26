<?php

namespace Thinktomorrow\Chief\Tests\Application\Site;

use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXml;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class SitemapTest extends ChiefTestCase
{
    private $carbon;

    private $sitemapXml;
    private $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();

        $this->carbon = Carbon::today();
        Carbon::setTestNow($this->carbon);

        $this->mockHandler = new MockHandler();
        $this->sitemapXml = new SitemapXml(new Client(['handler' => $this->mockHandler]));

        $page = ArticlePage::create(['current_state' => PageState::PUBLISHED]);
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $page2 = ArticlePage::create(['current_state' => PageState::PUBLISHED]);
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo', 'model_type' => $page2->getMorphClass(), 'model_id' => $page2->id]);

        $this->mockHandler->append(new Response(200));
        $this->mockHandler->append(new Response(200));
    }

    /** @test */
    public function it_can_generate_an_xml_per_locale()
    {
        $this->assertEquals($this->getExpectedXml(), $this->sitemapXml->generate('nl'));
    }

    /** @test */
    public function redirected_or_offline_urls_will_be_excluded()
    {
        $redirect = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'redirect_id' => 99, 'slug' => 'baz', 'model_type' => $redirect->getMorphClass(), 'model_id' => $redirect->id]);

        $offlinePage = ArticlePage::create();
        $offlinePage->changeStateOf('current_state', PageState::DRAFT);
        $offlinePage->save();

        UrlRecord::create(['locale' => 'nl', 'slug' => 'fooz', 'model_type' => $offlinePage->getMorphClass(), 'model_id' => $offlinePage->id]);

        $this->mockHandler->append(new Response(200));
        $this->mockHandler->append(new Response(200));

        $this->assertEquals($this->getExpectedXml(), $this->sitemapXml->generate('nl'));
    }

    /** @test */
    public function non_visitable_urls_will_be_excluded()
    {
        $redirect = ArticlePage::create(['current_state' => PageState::PUBLISHED]);
        UrlRecord::create(['locale' => 'nl', 'redirect_id' => 99, 'slug' => 'baz', 'model_type' => $redirect->getMorphClass(), 'model_id' => $redirect->id]);

        $this->mockHandler->append(new Response(404));

        $this->assertEquals($this->getExpectedXml(), $this->sitemapXml->generate('nl'));
    }

    private function getExpectedXml()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
    <url>
        <loc>http://localhost/bar</loc>
        <lastmod>'.$this->carbon->format(DateTime::ATOM).'</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>http://localhost/foo</loc>
        <lastmod>'.$this->carbon->format(DateTime::ATOM).'</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
</urlset>';
    }
}
