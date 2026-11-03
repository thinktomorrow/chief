<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

final class DocSource
{
    /**
     * @param  null|string  $namespace  Null for the project's own, un-namespaced guides.
     * @param  list<string>  $excludedFolders  Top-level folders whose guides are skipped.
     * @param  null|string  $overridePath  The project folder whose files replace this source's guides of the same ID.
     */
    public function __construct(
        public readonly ?string $namespace,
        public readonly string $path,
        public readonly array $excludedFolders = [],
        public readonly ?string $overridePath = null,
    ) {}

    /**
     * The ID of a Markdown file within the source, without namespace: its path
     * without `.md`, and a section's `index.md` at the folder's path.
     */
    public function relativeIdOf(string $relativePath): string
    {
        $relativeId = preg_replace('/\.md$/i', '', $relativePath);

        return basename($relativeId) === 'index' && str_contains($relativeId, '/') ? dirname($relativeId) : $relativeId;
    }

    public function guideId(string $relativeId): string
    {
        return $this->namespace === null ? $relativeId : $this->namespace.'::'.$relativeId;
    }

    /**
     * The file a guide is read from: the project's override when there is one, otherwise the
     * source's own file. An override is keyed by guide ID, so a section page shipped as
     * paginas/index.md is overridden by paginas.md as well as by paginas/index.md.
     */
    public function guideFile(string $relativeId, string $relativePath): string
    {
        if ($this->overridePath !== null) {
            foreach ([$relativeId.'.md', $relativeId.'/index.md'] as $overrideRelativePath) {
                $overrideFile = $this->overridePath.'/'.$overrideRelativePath;

                if (is_file($overrideFile)) {
                    return $overrideFile;
                }
            }
        }

        return $this->path.'/'.$relativePath;
    }

    /**
     * The file an asset is served from: the project's override at the same path when there
     * is one, otherwise the source's own file. Null when neither exists.
     */
    public function assetFile(string $relativePath): ?string
    {
        foreach ([$this->overridePath, $this->path] as $folder) {
            if ($folder !== null && is_file($folder.'/'.$relativePath)) {
                return $folder.'/'.$relativePath;
            }
        }

        return null;
    }

    public function isExcluded(string $relativePath): bool
    {
        foreach ($this->excludedFolders as $folder) {
            if (str_starts_with($relativePath, $folder.'/')) {
                return true;
            }
        }

        return false;
    }
}
