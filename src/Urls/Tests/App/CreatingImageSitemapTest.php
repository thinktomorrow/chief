<?php

namespace Thinktomorrow\Chief\Urls\Tests\App;

use Carbon\Carbon;
use Thinktomorrow\Chief\Assets\Tests\TestSupport\TestingFileUploads;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Sitemap\ImageSitemapXml;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\App\Actions\UpdateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;

class CreatingImageSitemapTest extends ChiefTestCase
{
    use TestingFileUploads;

    private ImageSitemapXml $sitemapXml;

    private UrlApplication $application;

    private Visitable $model;

    private RedirectApplication $redirectApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->application = app(UrlApplication::class);
        $this->redirectApplication = app(RedirectApplication::class);
        $this->sitemapXml = app(ImageSitemapXml::class);

        $carbon = Carbon::today();
        Carbon::setTestNow($carbon);

        // Create model with url
        $this->model = $this->setUpAndCreateArticle([
            'current_state' => PageState::published->value,
        ]);
        $this->application->create(new CreateUrl($this->model->modelReference(), 'nl', 'bar', 'online'));
        $this->application->create(new CreateUrl($this->model->modelReference(), 'en', 'baz', 'online'));

    }

    public function test_it_can_generate_from_model_asset(): void
    {
        $this->uploadImageField($this->model, 'thumb', 'test/image.png', [
            'fieldValues' => [
                'alt' => 'caption',
            ],
        ]);

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlWithModelAsset(), $this->sitemapXml->generate('nl'));
    }

    public function test_it_can_generate_per_locale()
    {
        $this->uploadImageField($this->model, 'thumb', 'test/image.png', [
            'fieldValues' => [
                'alt' => [
                    'nl' => 'caption nl',
                    'en' => 'caption en',
                ],
            ],
        ]);

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlWithLocalizedAsset(), $this->sitemapXml->generate('en'));
    }

    public function test_it_can_generate_images_from_fragment(): void
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->model, ['nl'], ['nl']);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id, null, 0, ['custom' => 'foobar']);

        $this->uploadImageField($fragment, 'thumb', 'test/image.png', [
            'fieldValues' => [
                'alt' => 'caption',
            ],
        ]);

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlWithFragmentAsset(), $this->sitemapXml->generate('nl'));
    }

    public function test_it_does_not_generate_images_for_offline_context(): void
    {
        // Creating an offline context
        $context = FragmentTestHelpers::findOrCreateContext($this->model, ['nl'], []);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id, null, 0, ['custom' => 'foobar']);

        $this->uploadImageField($fragment, 'thumb', 'test/image.png', [
            'fieldValues' => [
                'alt' => 'caption',
            ],
        ]);

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlWithOfflineContext(), $this->sitemapXml->generate('nl'));
    }

    public function test_it_does_not_generate_images_for_offline_urls(): void
    {
        $urlRecord = app(UrlRepository::class)->findBySlug('nl-base/bar', 'nl');
        $this->application->update(new UpdateUrl($urlRecord->id, 'nl', 'offline'));

        $this->uploadImageField($this->model, 'thumb', 'test/image.png', [
            'fieldValues' => [
                'alt' => 'caption',
            ],
        ]);

        $this->assertEqualsStringIgnoringStructure($this->getExpectedXmlWithOfflineUrl(), $this->sitemapXml->generate('nl'));
    }

    private function getExpectedXmlWithModelAsset()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
    <loc>http://localhost/nl-base/bar</loc>
    <image:image>
      <image:loc>http://localhost/storage/1/image.png</image:loc>
      <image:caption>caption</image:caption>
      <image:title>image.png</image:title>
    </image:image>
  </url>
</urlset>
';
    }

    private function getExpectedXmlWithLocalizedAsset()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
    <loc>http://localhost/en-base/baz</loc>
    <image:image>
      <image:loc>http://localhost/storage/1/image.png</image:loc>
      <image:caption>caption en</image:caption>
      <image:title>image.png</image:title>
    </image:image>
  </url>
</urlset>
';
    }

    private function getExpectedXmlWithFragmentAsset()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
    <loc>http://localhost/nl-base/bar</loc>
    <image:image>
      <image:loc>http://localhost/storage/1/image.png</image:loc>
      <image:caption>caption</image:caption>
      <image:title>image.png</image:title>
    </image:image>
  </url>
</urlset>
';
    }

    private function getExpectedXmlWithOfflineContext()
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url>
    <loc>http://localhost/nl-base/bar</loc>
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
