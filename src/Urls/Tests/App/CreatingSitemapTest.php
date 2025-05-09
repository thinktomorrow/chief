<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Sitemap\SitemapXml;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UpdateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;

class CreatingSitemapTest extends ChiefTestCase
{
    use TestingFileUploads;

    private $mockHandler;

    private SitemapXml $sitemapXml;

    private UrlApplication $application;

    private Visitable $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler;
        $this->sitemapXml = new SitemapXml(new Client(['handler' => $this->mockHandler]));

        $this->application = app(UrlApplication::class);

        $carbon = Carbon::today();
        Carbon::setTestNow($carbon);

        // Create model with url
        $this->model = $this->setUpAndCreateArticle([
            'current_state' => PageState::published->value,
        ]);

        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'bar', 'online'));
        $this->application->create(new CreateUrl($this->model->modelReference(), 'en', 'baz', 'online'));
    }

    public function test_it_can_generate_for_model(): void
    {
        $this->mockHandler->append(new Response(200));
        $this->mockHandler->append(new Response(200));

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlFromModel(), $this->sitemapXml->generate('nl'));
    }

    public function test_it_can_generate_per_locale()
    {
        $this->mockHandler->append(new Response(200));
        $this->mockHandler->append(new Response(200));

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlFromLocalizedModel(), $this->sitemapXml->generate('en'));
    }

    public function test_it_does_not_generate_sitemap_for_offline_urls(): void
    {
        $this->mockHandler->append(new Response(200));
        $this->mockHandler->append(new Response(200));

        $urlRecord = app(UrlRepository::class)->findBySlug('nl-base/bar', 'nl');
        $this->application->update(new UpdateUrl($urlRecord->id, 'nl', 'offline'));

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlWithOfflineUrl(), $this->sitemapXml->generate('nl'));
    }

    public function test_non_visitable_urls_will_be_excluded()
    {
        $this->mockHandler->append(new Response(404));

        $urlRecord = app(UrlRepository::class)->findBySlug('nl-base/bar', 'nl');

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlWithOfflineUrl(), $this->sitemapXml->generate('nl'));

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlWithOfflineUrl(), $this->sitemapXml->generate('nl'));
    }

    private function getExpectedXmlFromModel()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
    <loc>http://localhost/nl-base/bar</loc>
  </url>
</urlset>
';
    }

    private function getExpectedXmlFromLocalizedModel()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
    <loc>http://localhost/en-base/baz</loc>
  </url>
</urlset>
';
    }

    private function getExpectedXmlWithOfflineUrl()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
</urlset>
';
    }
}
