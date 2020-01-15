<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Snippets;

use Illuminate\Support\Collection;
use SplFileInfo;

class SnippetCollection extends Collection
{
    private static $loadedSnippets;

    public static function load(): SnippetCollection
    {
        if (static::$loadedSnippets) {
            return static::$loadedSnippets;
        }

        /** @var FileLoader $fileLoader */
        $fileLoader = app(FileLoader::class);

        $paths = config('thinktomorrow.chief.loadSnippetsFrom', []);

        /** @var SplFileInfo[] */
        $files = collect();

        foreach ($paths as $path) {
            if (! $fullpath = self::constructFullPath($path)) {
                continue;
            }

            // Load directory or single file
            if (is_dir($fullpath)) {
                $files = $files->merge($fileLoader->handleRecursive($fullpath));
            } elseif (is_file($fullpath)) {
                $files = $files->merge($fileLoader->handleFile($fullpath));
            }
        }

        return static::$loadedSnippets = new self($files->map(function (SplFileInfo $file) {
            $path = $file->getRealPath();

            if (0 === strpos($path, resource_path('views')) && false !== strpos($file->getBasename(), '.blade.php')) {
                $path = substr($path, strlen(resource_path('views')));
            }

            $key = substr($file->getBasename(), 0, strpos($file->getBasename(), '.'));
            $label = ucfirst(str_replace(['-','_'], ' ', $key));

            return new Snippet($key, $label, $path);
        })->all());
    }

    public static function find($key): ?Snippet
    {
        $loadedSnippets = static::load();

        return $loadedSnippets->first(function (Snippet $snippet) use ($key) {
            return $snippet->key() == $key;
        });
    }

    /**
     * If file is not found, it is possible a false value is passed so we ignore this early on
     * realpath() returns false if dir does not exists.
     * If then the directory still does not exists, we will silently abort and continue.
     *
     * @param $path
     * @return bool|string
     */
    private static function constructFullPath($path)
    {
        if (!$path) {
            return false;
        }

        $fullpath = base_path($path);

        // We will create a fullpath reference if the file does not exist as an extra safety measure.
        if (!is_dir($fullpath) && !file_exists($fullpath)) {
            $fullpath = realpath($path);
        }

        return $fullpath;
    }

    public function toClips(): array
    {
        return $this->map(function ($snippet) {
            return [$snippet->label(), $snippet->placeholder()];
        })->toArray();
    }

    public static function refresh()
    {
        static::$loadedSnippets = null;
    }

    public static function appearsEmpty(): bool
    {
        $paths = config('thinktomorrow.chief.loadSnippetsFrom', []);

        return empty($paths);
    }
}
