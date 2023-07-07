<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\TemporaryUploadedFile;
use Livewire\Wireable;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\FileHelper;

class UploadedFile implements Wireable
{
    private function __construct(
        public string $id,
        public ?string $mediaId, // Actually the asset id
        public ?string $previewUrl,
        public bool $isPreviewable,
        public ?string $tempPath,
        public string $filename,
        public string $size,
        public string $humanReadableSize,
        public string $mimeType,
        public string $extension,
        public ?string $imageWidth = null,
        public ?string $imageHeight = null,
        public bool $isUploading = true,
        public bool $isValidated = false,
        public bool $isQueuedForDeletion = false,
        public bool $isAttachedToModel = false,
        public array $fieldValues = [],
        public ?string $createdAt = null,
        public ?string $updatedAt = null,

        // Asset related values
        public array $urls = [],
        public array $owners = [],
    ) {

    }

    public function getUrl(string $conversionName = 'original'): ?string
    {
        return $this->urls[$conversionName] ?? null;
    }

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
            FileHelper::getImageWidth($file->getRealPath()),
            FileHelper::getImageHeight($file->getRealPath()),
            false,
            true,
            false,
            false,
            [],
            null,
            null,
            [],
            [],
        );
    }

    public static function fromAsset(Asset $asset): static
    {
        $media = $asset->getFirstMedia();

        if(! $media) {
            throw new \InvalidArgumentException('No media found for asset ' . $asset->id);
        }

        $urls = [
            'original' => $media->originalUrl,
            ...$media->getGeneratedConversions()->reject(fn ($isConverted) => false)->mapWithKeys(fn ($isConverted, $conversionName) => [$conversionName => $asset->getUrl($conversionName)])->all(),
        ];

        // Owners
        $owners = [];
        //        if($asset = $asset->model) {
        //
        //        }

        // TODO: convert this to using the new asset library api.
        // TODO: how to get the smallest conversions if we don't know the field info?
        $thumbUrl = $asset->getUrl('thumb');

        return new static(
            $asset->id,
            $asset->id,
            $thumbUrl,
            ('image' == $asset->getExtensionType()),
            null,
            $asset->getFileName() ?: '',
            $asset->getSize(),
            $asset->getHumanReadableSize(),
            $asset->getMimeType() ?: '',
            \Thinktomorrow\Chief\Assets\App\FileHelper::getExtension($asset->getFirstMediaPath()),
            $asset->getImageWidth(),
            $asset->getImageHeight(),
            false,
            true,
            false,
            true,
            $asset->pivot->data ?? [],
            $asset->created_at->getTimestamp(),
            $asset->updated_at->getTimestamp(),
            $urls,
            $owners,
        );
    }

    public static function fromPendingUploadedFile(string $id, string $fileName, int $fileSize): static
    {
        return new static(
            $id,
            null,
            null,
            false,
            null,

            // For new files - the basename can be changed so here we'll use the up to date filename
            $fileName,
            $fileSize,
            FileHelper::getHumanReadableSize($fileSize),
            'xxx',
            FileHelper::getExtension($fileName),
            null,
            null,
            true,
            true,
            false,
            false,
            [],
            null,
            null,
            [],
            [],
        );
    }

    public function toUploadedFile(): \Symfony\Component\HttpFoundation\File\UploadedFile
    {
        return new UploadedFile($this->tempPath, $this->filename, $this->mimeType);
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
            'imageWidth' => $this->imageWidth,
            'imageHeight' => $this->imageHeight,
            'isUploading' => $this->isUploading,
            'isValidated' => $this->isValidated,
            'isQueuedForDeletion' => $this->isQueuedForDeletion,
            'isAttachedToModel' => $this->isAttachedToModel,
            'fieldValues' => $this->fieldValues,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'urls' => $this->urls,
            'owners' => $this->owners,
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
