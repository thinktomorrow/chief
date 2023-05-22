<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Livewire\TemporaryUploadedFile;
use Livewire\Wireable;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\File\App\FileHelper;

class PreviewFile implements Wireable
{
    private function __construct(
        public string $id,
        public ?string $mediaId,
        public ?string $previewUrl,
        public bool $isPreviewable,
        public ?string $tempPath,
        public string $filename,
        public string $size,
        public string $humanReadableSize,
        public string $mimeType,
        public string $extension,
        public bool $isQueuedForDeletion = false,
        public bool $isAttachedToModel = false,
    ) {

    }
//
//    public function getOrderIndex(): int
//    {
//
//    }

    public function isImage(): bool
    {
        return FileHelper::isImage($this->mimeType);
    }

    public function getBaseName(): string
    {
        return FileHelper::getBaseName($this->filename);
    }

    public static function fromTemporaryUploadedFile(TemporaryUploadedFile $file, ?PreviewFile $current = null): static
    {
        return new static(
            $file->getFilename(),
            null,
            $file->isPreviewable() ? $file->temporaryUrl() : null,
            $file->isPreviewable(),
            $file->getRealPath(),

            // For new files - the basename can be changed so here we'll use the up to date filename
            $current ? $current->filename : $file->getClientOriginalName(),
            $file->getSize(),
            FileHelper::getHumanReadableSize($file->getSize()),
            $file->getMimeType(),
            FileHelper::getExtension($file->getClientOriginalName()),
            false,
            false,
        );
    }

    public static function fromAsset(Asset $asset): static
    {
        // TODO: convert this to using the new asset library api.
        // TODO: how to get the smallest conversions if we don't know the field info?
        $thumbUrl = $asset->url();

        try {
            $filesize = filesize($asset->getFirstMediaPath());
        } catch(\ErrorException $e) {
            $filesize = 0;
        }


        return new static(
            $asset->id,
            $asset->id,
            $thumbUrl,
            ('image' == $asset->getExtensionType()),
            null,
            $asset->filename(),
            $filesize,
            $asset->getSize(),  // asset->getSize() already returns human readable so this is first converted back to bytes
            //            File\App\FileHelper::getHumanReadableSize((int)$asset->getSize()),  // asset->getSize() already returns human readable so this is first converted back to bytes
            $asset->getMimeType(),
            File\App\FileHelper::getExtension($asset->getFirstMediaPath()),
            false,
            true,
        );
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'mediaId' => $this->mediaId,
            'previewUrl' => $this->previewUrl,
            'isPreviewable' => $this->isPreviewable,
            'tempPath' => $this->tempPath,
            'filename' => $this->filename,
            'size' => $this->size,
            'humanReadableSize' => $this->humanReadableSize,
            'mimeType' => $this->mimeType,
            'extension' => $this->extension,
            'isQueuedForDeletion' => $this->isQueuedForDeletion,
            'isAttachedToModel' => $this->isAttachedToModel,
        ];
    }

    public static function fromLivewire($value)
    {
        return static::fromArray($value);
    }

    public static function fromArray($value)
    {
        return new static(...$value);
    }
}
