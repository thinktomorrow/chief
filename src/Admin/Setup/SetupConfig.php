<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Setup;

final class SetupConfig
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function namespace(string $path = null): string
    {
        if ($path) {
            $parts = explode('/', rtrim($path, '/'));

            $parts = array_map(fn ($part) => ucfirst($part), $parts);

            return implode('\\', $parts);
        }

        if (isset($this->config['namespace'])) {
            return $this->config['namespace'];
        }

        return 'App\\Models';
    }

    public function namespacedClass(string $className): string
    {
        if (isset($this->config['namespace'])) {
            return '\\' . $this->config['namespace'] . ($className ? '\\' . $className : '');
        }

        return '\\App\\Models' . ($className ? '\\' . $className : '');
    }

    public function path(string $filename = null): string
    {
        if (isset($this->config['path'])) {
            return $this->config['path'] . '/' . $filename;
        }

        return 'app/Models/' . $filename;
    }
}
