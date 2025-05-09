<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Viewable;

class ViewPath
{
    private string $filePath;

    private ?string $ownerFolder;

    private ?string $baseFolder;

    final private function __construct(string $filePath, ?string $baseFolder = null, ?string $ownerFolder = null)
    {
        $this->filePath = $filePath;
        $this->ownerFolder = $ownerFolder;
        $this->baseFolder = $baseFolder;
    }

    public static function make(string $filepath, ?string $baseFolder = null, ?string $ownerFolder = null): self
    {
        return new static($filepath, $baseFolder, $ownerFolder);
    }

    /**
     * @throws NotFoundView
     */
    public function get(): string
    {
        $viewPaths = array_filter([
            $this->parseToPath([$this->baseFolder, $this->ownerFolder, $this->filePath]),
            $this->parseToPath([$this->ownerFolder, $this->filePath]),
            $this->parseToPath([$this->baseFolder, $this->filePath]),
            $this->parseToPath([$this->filePath, 'show']),
            $this->filePath,
        ], fn ($value) => $value); // removes null values due to the parsing

        foreach ($viewPaths as $path) {
            if (! view()->exists($path)) {
                continue;
            }

            return $path;
        }

        throw new NotFoundView('View file not found for ['.$this->filePath.']. Make sure to create one of the following views: ['.implode(', ', $viewPaths).'].');
    }

    private function parseToPath(array $parts): ?string
    {
        if (in_array(null, $parts)) {
            return null;
        }

        return implode('.', $parts);
    }
}
