<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Sitemap\ImageSitemapXml;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

class ImageSitemapTest extends ChiefTestCase
{
    private ImageSitemapXml $sitemapXml;

    private UrlApplication $application;

    private Visitable $model;

    private RedirectApplication $redirectApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->application = app(UrlApplication::class);
        $this->redirectApplication = app(RedirectApplication::class);

        $carbon = Carbon::today();
        Carbon::setTestNow($carbon);

        $this->sitemapXml = app(ImageSitemapXml::class);

        $this->model = $this->setUpAndCreateArticle(['current_state' => PageState::published->value]);
        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'bar', 'online'));

        // Add asset to page
        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image.png'))
            ->save();

        $asset->setData('alt', 'caption');
        $asset->save();

        app(AddAsset::class)->handle($this->model, $asset, 'image', 'nl', 0, []);

        [, $fragment] = FragmentTestHelpers::createContextAndAttachFragment($this->model, Quote::class, null, 0, ['custom' => 'foobar']);

        $asset = app(CreateAsset::class)
            ->uploadedFile(UploadedFile::fake()->image('image-2.png'))
            ->save();
        app(AddAsset::class)->handle($fragment->getFragmentModel(), $asset, 'image', 'nl', 0, []);
    }

    public function test_it_can_generate_an_xml_per_locale()
    {
        $this->assertEqualsStringIgnoringStructure($this->getExpectedXml(), $this->sitemapXml->generate('nl'));
    }

    private function getExpectedXml()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
    <loc>http://localhost/bar</loc>
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

    public function test_redirected_or_offline_urls_will_be_excluded()
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
