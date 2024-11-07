<?php

namespace Application\Site;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Sitemap\ImageSitemapXml;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXml;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class ImageSitemapTest extends ChiefTestCase
{
    private $carbon;
    private $sitemapXml;

    public function setUp(): void
    {
        parent::setUp();

        $this->carbon = Carbon::today();
        Carbon::setTestNow($this->carbon);

        $this->sitemapXml = new ImageSitemapXml();

        $page = $this->setUpAndCreateArticle(['current_state' => PageState::published]);
        UrlRecord::create(['locale' => 'nl', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        // Add asset to page
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $asset->setData('alt', 'caption');
        $asset->save();

        app(AddAsset::class)->handle($page, $asset, 'image', 'nl', 0, []);

        // Add asset to fragment of page
        $fragment = $this->setupAndCreateQuote($page);
        $this->addFragment($fragment, $page);

        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image-2.png'))
            ->save();
        app(AddAsset::class)->handle($fragment->fragmentModel(), $asset, 'image', 'nl', 0, []);
    }

    /** @test */
    public function it_can_generate_an_xml_per_locale()
    {
        $this->assertEqualsStringIgnoringStructure($this->getExpectedXml(), $this->sitemapXml->generate('nl'));
    }

    private function getExpectedXml()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
    <loc>http://localhost/bar</loc>
            <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    <image:image>
      <image:loc>http://localhost/storage/1/image.png</image:loc>
      <image:caption>caption</image:caption>
      <image:title>image.png</image:title>
    </image:image>
    <image:image>
      <image:loc>http://localhost/storage/2/image-2.png</image:loc>
      <image:title>image-2.png</image:title>
    </image:image>
  </url>
</urlset>
';
    }

    /** @test */
    public function redirected_or_offline_urls_will_be_excluded()
    {
        $redirect = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'redirect_id' => 99, 'slug' => 'baz', 'model_type' => $redirect->getMorphClass(), 'model_id' => $redirect->id]);

        $offlinePage = ArticlePage::create();
        $offlinePage->changeState('current_state', PageState::draft);
        $offlinePage->save();

        UrlRecord::create(['locale' => 'nl', 'slug' => 'fooz', 'model_type' => $offlinePage->getMorphClass(), 'model_id' => $offlinePage->id]);

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXml(), $this->sitemapXml->generate('nl'));
    }
}
