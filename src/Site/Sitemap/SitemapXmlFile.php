<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Sitemap;

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

        file_put_contents($filepath, $xmlString);

        // If this is the default locale, we'll create a default sitemap.xml as well
        if ($locale == config('app.fallback_locale')) {
            file_put_contents(public_path('sitemap.xml'), $xmlString);
        }
    }
}
