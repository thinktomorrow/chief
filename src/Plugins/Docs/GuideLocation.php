<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

/**
 * Where a guide is shipped: its source and its file within that source, such as
 * `paginas/index.md`. An override keeps the location of the guide it replaces.
 */
final class GuideLocation
{
    public function __construct(
        public readonly DocSource $source,
        public readonly string $path,
    ) {}

    /**
     * The ID of the guide at a file relative to this one in the same source, or
     * null when the relative file lies outside the source.
     */
    public function guideIdOf(string $relativeFile): ?string
    {
        $path = $this->pathOf($relativeFile);

        return $path === null ? null : $this->source->guideId($this->source->relativeIdOf($path));
    }

    /**
     * The path within the source of a file relative to this one, or null when the
     * relative file lies outside the source.
     */
    public function pathOf(string $relativeFile): ?string
    {
        $segments = dirname($this->path) === '.' ? [] : explode('/', dirname($this->path));

        foreach (explode('/', $relativeFile) as $segment) {
            if ($segment === '..') {
                if ($segments === []) {
                    return null;
                }

                array_pop($segments);
            } elseif ($segment !== '.' && $segment !== '') {
                $segments[] = $segment;
            }
        }

        return implode('/', $segments);
    }
}
