<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

use InvalidArgumentException;

/**
 * Registry of the folders the user guide is read from. The project's own
 * guides form the un-namespaced source; packages add namespaced ones. The
 * project overrides a namespaced guide with a file at the same path in
 * its vendor/<namespace> folder.
 */
final class DocSources
{
    /** The project folder that holds overrides and is not read as project guides. */
    private const OVERRIDES_FOLDER = 'vendor';

    /** @var array<string, DocSource> */
    private array $sources = [];

    private DocSource $projectSource;

    public function __construct(private readonly string $projectPath)
    {
        $this->projectSource = new DocSource(null, $projectPath, [self::OVERRIDES_FOLDER]);
    }

    public function add(string $namespace, string $path): void
    {
        if (isset($this->sources[$namespace])) {
            throw new InvalidArgumentException('Doc source namespace ['.$namespace.'] is already registered.');
        }

        $this->sources[$namespace] = new DocSource($namespace, $path, overridePath: $this->projectPath.'/'.self::OVERRIDES_FOLDER.'/'.$namespace);
    }

    public function has(string $namespace): bool
    {
        return isset($this->sources[$namespace]);
    }

    /**
     * @param  null|string  $namespace  Null for the project's own source.
     */
    public function find(?string $namespace): ?DocSource
    {
        return $namespace === null ? $this->projectSource : $this->sources[$namespace] ?? null;
    }

    /** @return list<DocSource> */
    public function all(): array
    {
        return [$this->projectSource, ...array_values($this->sources)];
    }
}
