<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Sitemap;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SitemapFiles
{
    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $directory
     * @return Collection (SplFileInfo[])
     */
    public function allWithin(string $directory): Collection
    {
        $files = $this->filesystem->files($directory);

        return collect($files)->filter(function ($file) {
            return (Str::startsWith($file->getFileName(), 'sitemap-') && Str::endsWith($file->getFileName(), '.xml'));
        });
    }
}
