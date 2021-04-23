<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Viewable;

class ViewPath
{
    private string $filePath;
    private ?string $ownerPath;
    private ?string $basePath;

    final private function __construct(string $filePath, string $basePath = null, string $ownerPath = null)
    {
        $this->filePath = $filePath;
        $this->ownerPath = $ownerPath;
        $this->basePath = $basePath;
    }

    public static function make(string $filepath, string $basePath = null, string $ownerPath = null): self
    {
        return new static($filepath, $basePath, $ownerPath);
    }

    /**
     * @return string
     * @throws NotFoundView
     */
    public function get(): string
    {
        $viewPaths = [
            $this->parseToPath([$this->basePath, $this->ownerPath, $this->filePath]),
            $this->parseToPath([$this->basePath, $this->filePath]),
            $this->parseToPath([$this->filePath, 'show']),
            $this->parseToPath([$this->basePath, 'show']),
        ];

        foreach ($viewPaths as $path) {
            if (! view()->exists($path)) {
                continue;
            }

            return $path;
        }

        if (! view()->exists(last($viewPaths))) {
            throw new NotFoundView('View file not found for [' . $this->filePath . ']. Make sure to create a [' . $viewPaths[1] . '] view.');
        }
    }

    private function parseToPath(array $parts): string
    {
        $viewPath = array_filter($parts, fn($value) => $value);

        return implode('.', $viewPath);
    }
}
