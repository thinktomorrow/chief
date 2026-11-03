<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Links;

use Thinktomorrow\Chief\Plugins\Docs\GuideNode;

/**
 * The target of a link to another guide: a relative `.md` file or a namespaced guide ID.
 */
final class GuideLinkTarget
{
    /**
     * @param  string  $fragment  The `#…` anchor including the hash, or an empty string.
     */
    private function __construct(
        private readonly ?string $relativeFile,
        private readonly ?string $guideId,
        public readonly string $fragment,
    ) {}

    /**
     * The guide target of a link URL, or null for a URL that does not point at a guide,
     * such as one with a scheme (`https:`, `mailto:`) or an absolute path.
     */
    public static function parse(string $url): ?self
    {
        $path = str_contains($url, '#') ? strstr($url, '#', true) : $url;
        $fragment = (string) substr($url, strlen($path));

        if (preg_match('/^[a-z][a-z0-9+.-]*:(?!:)/i', $path) || str_starts_with($path, '/')) {
            return null;
        }

        if (str_contains($path, '::')) {
            return new self(null, $path, $fragment);
        }

        if (str_ends_with(strtolower($path), '.md')) {
            return new self(rawurldecode($path), null, $fragment);
        }

        return null;
    }

    /**
     * The ID of the targeted guide, or null when a relative file cannot be resolved from the current guide.
     */
    public function guideId(GuideNode $currentGuide): ?string
    {
        if ($this->guideId !== null) {
            return $this->guideId;
        }

        return $currentGuide->location?->guideIdOf($this->relativeFile);
    }
}
