<?php

namespace Thinktomorrow\Chief\Plugins\Upgrade;

use Illuminate\Filesystem\Filesystem;

class ReplaceTextInFile
{
    private Filesystem $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function replace($filepath, $existingValue, $newValue): void
    {
        if (! $this->files->exists($filepath)) {
            throw new \InvalidArgumentException('File '.$filepath.' does not exist.');
        }

        $this->files->replaceInFile($existingValue, $newValue, $filepath);
    }
}
