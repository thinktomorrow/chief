<?php

namespace Thinktomorrow\Chief\Plugins\Upgrade;

use Illuminate\Filesystem\Filesystem;

class ListProjectFiles
{
    private Filesystem $files;

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function get(array $directories = [], array $excludedDirectories = []): array
    {
        $directories = count($directories) > 0 ? $directories : $this->defaultDirectories();

        if (count($excludedDirectories) > 0) {
            $directories = array_diff($directories, $excludedDirectories);
        }

        return $this->getFilesInDirectories($directories);
    }

    private function defaultDirectories(): array
    {
        return [
            base_path('app'),
            base_path('config'),
            base_path('bootstrap'),
            base_path('database'),
            base_path('resources'),
            base_path('routes'),
            base_path('tests'),
        ];
    }

    private function getFilesInDirectories(array $directories)
    {
        $files = [];

        foreach ($directories as $directory) {
            $files = array_merge($files, $this->files->allFiles($directory));
        }

        return $files;
    }
}
