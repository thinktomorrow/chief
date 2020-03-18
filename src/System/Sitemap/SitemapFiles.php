<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\System\Sitemap;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class SitemapFiles
{
    /** @var Filesystem */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        trap($filesystem);
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
