<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\System\Sitemap;

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
    }
}
