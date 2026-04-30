<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Sitemap;

use Illuminate\Support\Facades\File;

class SitemapXmlFile
{
    /** @var SitemapXml */
    private $sitemapXml;

    public function __construct(SitemapXml $sitemapXml)
    {
        $this->sitemapXml = $sitemapXml;
    }

    public function create(string $locale, string $filepath, array $alternateLocales = []): void
    {
        $xmlString = $this->sitemapXml->generate($locale, $alternateLocales);

        File::ensureDirectoryExists(dirname($filepath));
        file_put_contents($filepath, $xmlString);

        // If this is the default locale, we'll create a default sitemap.xml as well
        if ($locale == config('app.fallback_locale')) {
            file_put_contents(dirname($filepath).'/sitemap.xml', $xmlString);
        }
    }
}
