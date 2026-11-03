<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Media;

/**
 * The file types a guide can show as an image or video, keyed by extension.
 */
enum AssetType: string
{
    case Png = 'png';
    case Jpg = 'jpg';
    case Jpeg = 'jpeg';
    case Gif = 'gif';
    case Webp = 'webp';
    case Avif = 'avif';
    case Svg = 'svg';
    case Mp4 = 'mp4';
    case Webm = 'webm';

    /**
     * The type of a file path or URL by its extension, ignoring case, a query and an anchor.
     */
    public static function fromPath(string $path): ?self
    {
        $path = (string) preg_replace('/[?#].*$/s', '', $path);

        return self::tryFrom(strtolower(pathinfo($path, PATHINFO_EXTENSION)));
    }

    /**
     * The route requirement for a path ending in one of the allowed extensions.
     */
    public static function routePattern(): string
    {
        return '.+\.(?i:'.implode('|', array_column(self::cases(), 'value')).')';
    }

    public function contentType(): string
    {
        return match ($this) {
            self::Png => 'image/png',
            self::Jpg, self::Jpeg => 'image/jpeg',
            self::Gif => 'image/gif',
            self::Webp => 'image/webp',
            self::Avif => 'image/avif',
            self::Svg => 'image/svg+xml',
            self::Mp4 => 'video/mp4',
            self::Webm => 'video/webm',
        };
    }

    public function isVideo(): bool
    {
        return $this === self::Mp4 || $this === self::Webm;
    }
}
