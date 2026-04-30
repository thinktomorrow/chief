<?php

namespace Thinktomorrow\Chief\App\Console;

use Illuminate\Support\Facades\File;
use Thinktomorrow\Chief\Site\Sitemap\ImageSitemapXmlFile;
use Thinktomorrow\Chief\Sites\ChiefSites;

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
        $locales = ChiefSites::locales();
        $directory = storage_path('app/feeds');

        File::ensureDirectoryExists($directory);

        foreach ($locales as $locale) {
            $filepath = $directory.'/image-sitemap-'.$locale.'.xml';

            $this->info('Generating an image sitemap for locale: '.$locale.' at: '.$filepath);

            $this->sitemapXmlFile->create($locale, $filepath);
        }

        $this->info('Done generating sitemaps.');
    }
}
