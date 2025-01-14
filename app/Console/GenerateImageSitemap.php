<?php

namespace Thinktomorrow\Chief\App\Console;

use Thinktomorrow\Chief\Site\Sitemap\ImageSitemapXmlFile;

class GenerateImageSitemap extends BaseCommand
{
    protected $signature = 'chief:image-sitemap';
    protected $description = 'Generate an image sitemap for all locales. Only online and visitable image urls are included.';
    /**
     * @var ImageSitemapXmlFile
     */
    private $sitemapXmlFile;

    public function __construct(ImageSitemapXmlFile $sitemapXmlFile)
    {
        parent::__construct();

        $this->sitemapXmlFile = $sitemapXmlFile;
    }

    public function handle(): void
    {
        $locales = config('chief.locales');

        foreach ($locales as $locale) {
            $filepath = public_path('image-sitemap-' . $locale . '.xml');

            $this->info('Generating an image sitemap for locale: ' . $locale . ' at: ' . $filepath);

            $this->sitemapXmlFile->create($locale, $filepath);
        }

        $this->info('Done generating sitemaps.');
    }
}
