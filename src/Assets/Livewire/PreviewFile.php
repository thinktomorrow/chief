<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Wireable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\AssetLibrary\External\ExternalAssetContract;
use Thinktomorrow\Chief\Assets\App\FileHelper;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentOwnerRepository;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class PreviewFile implements Wireable
{
    private function __construct(
        public string  $id,
        public ?string $mediaId, // The actual Asset id
        public ?string $previewUrl,
        public bool    $isPreviewable,
        public ?string $tempPath,
        public string  $filename,
        public string  $size,
        public string  $humanReadableSize,
        public string  $mimeType,
        public string  $extension,
        public ?string $width = null,
        public ?string $height = null,
        public bool    $isUploading = true,
        public bool    $isValidated = false,
        public bool    $isQueuedForDeletion = false,
        public bool    $isAttachedToModel = false,
        public bool    $isExternalAsset = false,
        public array   $fieldValues = [],
        public ?string $validationMessage = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,

        // Asset related values
        public array   $data = [],
        public array   $urls = [],
        public array   $owners = [],
    ) {

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
            false,
            [],
            null,
            null,
            null,
            [],
            [],
            [],
        );
    }

    public static function fromAsset(AssetContract $asset): static
    {
        if ($asset instanceof ExternalAssetContract) {
            return static::fromExternalAsset($asset);
        }

        return static::fromLocalAsset($asset);
    }

    private static function fromExternalAsset(ExternalAssetContract $asset): static
    {
        $previewUrls = [
            'original' => $asset->getUrl(),
            'thumb' => $thumbUrl = $asset->getPreviewUrl('thumb'),
        ];

        $owners = [];

        return new static(
            $asset->id,
            $asset->id,
            $thumbUrl,
            ('image' == $asset->getPreviewExtensionType()),
            null,
            $asset->getFileName() ?: '',
            $asset->getSize(),
            $asset->getHumanReadableSize(),
            $asset->getMimeType() ?: '',
            '',
            $asset->getWidth(),
            $asset->getHeight(),
            false,
            true,
            false,
            true,
            true,
            array_merge($asset->data ?? [], $asset->pivot->data ?? []),
            null,
            $asset->created_at->getTimestamp(),
            $asset->updated_at->getTimestamp(),
            ($asset->data ?: []),
            $previewUrls,
            $owners,
        );
    }

    public function getUrl(string $conversionName = 'original'): ?string
    {
        return $this->urls[$conversionName] ?? null;
    }

    private static function fromLocalAsset(Asset $asset): static
    {
        $media = $asset->getFirstMedia();

        if (! $media) {
            throw new InvalidArgumentException('No media found for asset ' . $asset->id);
        }

        $urls = [
            'original' => $asset->getUrl(),
            ...$media->getGeneratedConversions()->reject(fn ($isConverted) => false)->mapWithKeys(fn ($isConverted, $conversionName) => [$conversionName => $asset->getUrl($conversionName)])->all(),
        ];

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
            FileHelper::getExtension($asset->getFirstMediaPath()),
            $asset->getWidth(),
            $asset->getHeight(),
            false,
            true,
            false,
            true,
            false,
            array_merge($asset->data ?? [], $asset->pivot->data ?? []),
            null,
            $asset->created_at->getTimestamp(),
            $asset->updated_at->getTimestamp(),
            ($asset->data ?: []),
            $urls,
            [],
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
            false,
            [],
            null,
            null,
            null,
            [],
            [],
            [],
        );
    }

    public static function fromLivewire($value)
    {
        return static::fromArray($value);
    }

    public static function fromArray($value)
    {
        return new static(...$value);
    }

    public function isImage(): bool
    {
        return FileHelper::isImage($this->mimeType);
    }

    public function isVideo(): bool
    {
        return FileHelper::isVideo($this->mimeType);
    }

    public function getBaseName(): string
    {
        return FileHelper::getBaseName($this->filename);
    }

    public function toUploadedFile(): UploadedFile
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
            'width' => $this->width,
            'height' => $this->height,
            'isUploading' => $this->isUploading,
            'isValidated' => $this->isValidated,
            'isQueuedForDeletion' => $this->isQueuedForDeletion,
            'isAttachedToModel' => $this->isAttachedToModel,
            'isExternalAsset' => $this->isExternalAsset,
            'fieldValues' => $this->fieldValues,
            'validationMessage' => $this->validationMessage,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'data' => $this->data,
            'urls' => $this->urls,
            'owners' => $this->owners,
        ];
    }

    public function loadOwners(): void
    {
        if (! $this->mediaId) {
            return;
        }

        $references = DB::table('assets_pivot')
            ->select(['entity_type', 'entity_id'])
            ->where('asset_id', $this->mediaId)
            ->get();

        foreach ($references as $reference) {
            $model = ModelReference::make($reference->entity_type, $reference->entity_id)->instance();

            if ($model instanceof FragmentModel) {
                $ownerModels = app(FragmentOwnerRepository::class)->getOwners($model);
                foreach ($ownerModels as $ownerModel) {
                    $this->owners[] = $this->createOwnerFields($ownerModel);
                }

                continue;
            }

            $this->owners[] = $this->createOwnerFields($model);
        }
    }

    private function createOwnerFields($model): array
    {
        try {
            $resource = app(Registry::class)->findResourceByModel($model::class);
            $manager = app(Registry::class)->findManagerByModel($model::class);

            return [
                'label' => $resource->getPageTitle($model),
                'adminUrl' => $manager->route('edit', $model),
                'modelReference' => $model->modelReference()->get(),
            ];
        } catch (Exception $e) {
            report($e);
        }

        return ['label' => null, 'adminUrl' => null, 'modelReference' => null];
    }

    public function hasData(string $key): bool
    {
        return Arr::has($this->data, $key);
    }

    public function getExternalAssetType(): ?string
    {
        return $this->getData('external.type');
    }

    public function getData(string $key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }
}
