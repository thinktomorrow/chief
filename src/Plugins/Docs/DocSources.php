<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

use InvalidArgumentException;

/**
 * Registry of the folders the user guide is read from. The project's own
 * guides form the un-namespaced source; packages add namespaced ones.
 */
final class DocSources
{
    /** @var array<string, DocSource> */
    private array $sources = [];

    private DocSource $projectSource;

    public function __construct(string $projectPath)
    {
        $this->projectSource = new DocSource(null, $projectPath, ['vendor']);
    }

    public function add(string $namespace, string $path): void
    {
        if (isset($this->sources[$namespace])) {
            throw new InvalidArgumentException('Doc source namespace ['.$namespace.'] is already registered.');
        }

        $this->sources[$namespace] = new DocSource($namespace, $path);
    }

    /** @return list<DocSource> */
    public function all(): array
    {
        return [$this->projectSource, ...array_values($this->sources)];
    }
}
