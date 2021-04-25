<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Viewable;

class ViewPath
{
    private string $filePath;
    private ?string $ownerFolder;
    private ?string $baseFolder;

    final private function __construct(string $filePath, string $baseFolder = null, string $ownerFolder = null)
    {
        $this->filePath = $filePath;
        $this->ownerFolder = $ownerFolder;
        $this->baseFolder = $baseFolder;
    }

    public static function make(string $filepath, string $baseFolder = null, string $ownerFolder = null): self
    {
        return new static($filepath, $baseFolder, $ownerFolder);
    }

    /**
     * @return string
     * @throws NotFoundView
     */
    public function get(): string
    {
        $viewPaths = [
            $this->parseToPath([$this->baseFolder, $this->ownerFolder, $this->filePath]),
            $this->parseToPath([$this->ownerFolder, $this->filePath]),
            $this->parseToPath([$this->baseFolder, $this->filePath]),
            $this->parseToPath([$this->filePath, 'show']),
            $this->filePath,
        ];

        foreach ($viewPaths as $path) {
            if (! $path || ! view()->exists($path)) {
                continue;
            }

            return $path;
        }

        if (! view()->exists(last($viewPaths))) {
            throw new NotFoundView('View file not found for [' . $this->filePath . ']. Make sure to create a [' . last($viewPaths) . '] view.');
        }
    }

    private function parseToPath(array $parts): ?string
    {
        if (in_array(null, $parts)) {
            return null;
        }

        return implode('.', $parts);
    }
}
