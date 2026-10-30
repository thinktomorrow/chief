<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Media;

use Thinktomorrow\Chief\Plugins\Docs\DocSource;
use Thinktomorrow\Chief\Plugins\Docs\DocSources;
use Thinktomorrow\Chief\Plugins\Docs\GuideLocation;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;

/**
 * An image or video that lives next to the guides of a source, such as
 * `paginas/status-knop.png`. Its ID mirrors its location the way a guide ID
 * does (`chief::paginas/status-knop.png`).
 */
final class GuideAsset
{
    private function __construct(
        public readonly DocSource $source,
        public readonly string $path,
        public readonly AssetType $type,
    ) {}

    /**
     * The asset with the given ID, or null when the ID is not one of an allowed type within a known source.
     */
    public static function fromId(DocSources $sources, string $assetId): ?self
    {
        [$namespace, $path] = str_contains($assetId, '::') ? explode('::', $assetId, 2) : [null, $assetId];

        $source = $sources->find($namespace);

        return $source === null ? null : self::at($source, $path);
    }

    /**
     * The asset a guide references by a path relative to its own file, or null when the
     * path lies outside the source or is not of an allowed type.
     */
    public static function referencedFrom(GuideLocation $location, string $relativeFile): ?self
    {
        $path = $location->pathOf($relativeFile);

        return $path === null ? null : self::at($location->source, $path);
    }

    private static function at(DocSource $source, string $path): ?self
    {
        $segments = explode('/', $path);

        if (array_intersect($segments, ['', '.', '..']) !== [] || preg_match('/[\\\\\x00]/', $path) || $source->isExcluded($path)) {
            return null;
        }

        $type = AssetType::fromPath($path);

        return $type === null ? null : new self($source, $path, $type);
    }

    public function id(): string
    {
        return $this->source->guideId($this->path);
    }

    /**
     * The file the asset is served from, or null when it doesn't exist.
     */
    public function file(): ?string
    {
        return $this->source->assetFile($this->path);
    }

    /**
     * The asset's admin URL, versioned by the file's modification time, or null when it doesn't exist.
     */
    public function url(): ?string
    {
        $file = $this->file();

        return $file === null ? null : route('chief.docs.asset', $this->id()).'?v='.filemtime($file);
    }

    /**
     * Whether the asset's section is in the visible tree. Its section is the nearest
     * folder holding it that is a node of the guide tree, so an asset in a folder of
     * its own belongs to the section around it. An asset outside any section is visible.
     */
    public function isVisibleIn(GuideTree $tree): bool
    {
        for ($folder = dirname($this->path); $folder !== '.'; $folder = dirname($folder)) {
            $folderId = $this->source->guideId($folder);

            if ($tree->find($folderId) !== null) {
                return true;
            }

            if ($tree->isWithheld($folderId)) {
                return false;
            }
        }

        return true;
    }
}
