<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Setup;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Concerns\InteractsWithIO;

final class FileManipulation
{
    use InteractsWithIO;

    private Filesystem $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function writeFile(string $path, string $content, bool $overwriteIfExists = false): void
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        if ($this->files->exists($path) && ! $overwriteIfExists) {
            $this->error('Class already exists at '.str_replace(base_path(), '', $path.''));
            return;
        }

        file_put_contents($path, $content);
        $this->info('File '.str_replace(base_path(), '', $path.'').' created.');
    }

    public function addToMethod($filepath, $method, $content)
    {
        $originalContent = file_get_contents($filepath);
        $replacedContent = preg_replace('#('.$method.'\(\)\s*{)#', "$1\n        " . $content, $originalContent);

        file_put_contents($filepath, $replacedContent);
    }
}
