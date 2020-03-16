<?php

namespace Thinktomorrow\Chief\App\Console;

use Thinktomorrow\Chief\System\Sitemap\SitemapXmlFile;

class GenerateSitemap extends BaseCommand
{
    protected $signature    = 'chief:sitemap';
    protected $description  = 'Generate a sitemap for all locales. Only online and visitable urls are included.';
    /**
     * @var SitemapXmlFile
     */
    private $sitemapXmlFile;

    public function __construct(SitemapXmlFile $sitemapXmlFile)
    {
        parent::__construct();

        $this->sitemapXmlFile = $sitemapXmlFile;
    }

    public function handle()
    {
        $locales = config('translatable.locales');

        foreach($locales as $key => $locale) {
            $filepath = public_path('sitemap-'.$locale.'.xml');

            $this->info('Generating a sitemap for locale: '.$locale . ' at: ' .  $filepath);

            $this->sitemapXmlFile->create($locale, $filepath);
        }

        $this->info('Done generating sitemaps.');
    }
}
