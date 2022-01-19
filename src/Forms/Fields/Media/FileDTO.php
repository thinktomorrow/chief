<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Media;

use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Forms\Fields\File;

class FileDTO
{
    public readonly int $id;
    public readonly string $filename;
    public readonly string $url;
    public readonly string $thumbUrl;
    public readonly string $mimeType;
    public readonly bool $isImage;
    public readonly string $size;
    public readonly string $extension;

    public function __construct(int $id, string $filename, string $url, string $thumbUrl, string $mimeType, bool $isImage, string $size, string $extension)
    {
        $this->id = $id;
        $this->filename = $filename;
        $this->url = $url;
        $this->thumbUrl = $thumbUrl;
        $this->mimeType = $mimeType;
        $this->isImage = $isImage;
        $this->size = $size;
        $this->extension = $extension;
    }

    public static function fromAsset(File $field, Asset $asset): static
    {
        $url = $field->isStoredOnPublicDisk()
            ? $asset->url()
            : ($field->generatesCustomUrl() ? $field->generateCustomUrl($asset, $field->getModel()) : '');

        $thumbUrl = $field->isStoredOnPublicDisk()
            ? $asset->url('thumb')
            : '';

        // If the conversions haven't run yet, we'll use the original image until they are uploaded
        if ($field->isStoredOnPublicDisk() && ! file_exists(public_path($thumbUrl))) {
            $thumbUrl = $asset->url();
        }

        return new static(
            $asset->id,
            $asset->filename(),
            $url,
            $thumbUrl,
            $asset->getMimeType(),
            ('image' == $asset->getExtensionType()),
            $asset->getSize(),
            $asset->getExtensionType(),
        );
    }
}
