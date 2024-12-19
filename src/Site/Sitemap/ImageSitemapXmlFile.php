<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Sitemap;

class ImageSitemapXmlFile
{
    /** @var ImageSitemapXml */
    private $sitemapXml;

    public function __construct(ImageSitemapXml $sitemapXml)
    {
        $this->sitemapXml = $sitemapXml;
    }

    public function create(string $locale, string $filepath): void
    {
        $xmlString = $this->sitemapXml->generate($locale);

        file_put_contents($filepath, $xmlString);

        // If this is the default locale, we'll create a default sitemap.xml as well
        if ($locale == config('app.fallback_locale')) {
            file_put_contents(public_path('image-sitemap.xml'), $xmlString);
        }
    }
}
