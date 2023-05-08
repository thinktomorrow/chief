<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\App;

use Illuminate\Support\Str;

class FileHelper
{
    public static function isImage(string $mimeType): bool
    {
        return Str::endsWith($mimeType, [
            'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'
        ]);
    }

    public static function getExtension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

    public static function getHumanReadableSize(int $sizeInBytes): string
    {
        [$size, $unit] = explode(' ',  \Spatie\MediaLibrary\Support\File::getHumanReadableSize($sizeInBytes));

        return round($size) . ' ' . $unit;
    }

    public static function getBaseName(string $path): string
    {
        return basename($path, '.'.static::getExtension($path));
    }
}
