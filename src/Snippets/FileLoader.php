<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Snippets;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

class FileLoader
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function handleFile(string $filepath): Collection
    {
        return collect([new \SplFileInfo($filepath)]);
    }

    public function handleRecursive(string $directory): Collection
    {
        return $this->handle($directory, true);
    }

    public function handle(string $directory, $recursive = false): Collection
    {
        $files = $recursive ? $this->filesystem->allFiles($directory) : $this->filesystem->files($directory);

        return collect($files);
    }
}
