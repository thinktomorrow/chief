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
use Thinktomorrow\AssetLibrary\AssetType\AssetTypeFactory;
use Thinktomorrow\AssetLibrary\External\ExternalAssetContract;
use Thinktomorrow\Chief\Assets\App\FileHelper;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentFactory;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentOwnerRepository;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class PreviewFile implements Wireable
{
    const DEFAULT_ASSETTYPE = 'default';

    private function __construct(
        public string $id,
        public ?string $mediaId, // The actual Asset id
        public ?string $previewUrl,
        public bool $isPreviewable,
        public ?string $tempPath,
        public string $assetType,
        public string $filename,
        public string $size,
        public string $humanReadableSize,
        public string $mimeType,
        public string $extension,
        public ?string $width = null,
        public ?string $height = null,
        public bool $isUploading = true,
        public bool $isValidated = false,
        public bool $isQueuedForDeletion = false,
        public bool $isAttachedToModel = false,
        public bool $isExternalAsset = false,
        public array $fieldValues = [],
        public ?string $validationMessage = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,

        // Asset related values
        public array $data = [],
        public array $urls = [],
        public array $owners = [],
    ) {}

    public static function fromTemporaryUploadedFile(TemporaryUploadedFile $file, ?PreviewFile $current = null, array $attributes = []): static
    {
        $model = new static(
            $file->getFilename(),
            null,
            $file->isPreviewable() ? $file->temporaryUrl() : null,
            $file->isPreviewable(),
            $file->getRealPath(),
            self::DEFAULT_ASSETTYPE,
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

        foreach ($attributes as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }

    public static function fromAsset(AssetContract $asset, array $attributes = []): static
    {
        if ($asset instanceof ExternalAssetContract) {
            return static::fromExternalAsset($asset);
        }

        $model = static::fromLocalAsset($asset);

        foreach ($attributes as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }

    private static function fromExternalAsset(ExternalAssetContract $asset, array $attributes = []): static
    {
        $previewUrls = [
            'original' => $asset->getUrl(),
            'thumb' => $thumbUrl = $asset->getPreviewUrl('thumb'),
        ];

        $owners = [];

        $model = new static(
            $asset->id,
            $asset->id,
            $thumbUrl,
            ($asset->getPreviewExtensionType() == 'image'),
            null,
            AssetTypeFactory::assetTypeByClassName(get_class($asset)),
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

        foreach ($attributes as $key => $value) {
            $model->$key = $value;
        }

        return $model;
    }

    public function getUrl(string $conversionName = 'original'): ?string
    {
        return $this->urls[$conversionName] ?? (count($this->urls) > 0 ? reset($this->urls) : null);
    }

    private static function fromLocalAsset(Asset $asset): static
    {
        $media = $asset->getFirstMedia();

        if (! $media) {
            throw new InvalidArgumentException('No media found for asset '.$asset->id);
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
            ($asset->getExtensionType() == 'image'),
            null,
            AssetTypeFactory::assetTypeByClassName(get_class($asset)),
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
            self::DEFAULT_ASSETTYPE,

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
            'assetType' => $this->assetType,
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

        $this->owners = [];

        $references = DB::table('assets_pivot')
            ->select(['entity_type', 'entity_id'])
            ->where('asset_id', $this->mediaId)
            ->get();

        foreach ($references as $reference) {
            $model = ModelReference::make($reference->entity_type, $reference->entity_id)->instance();

            if ($model instanceof FragmentModel) {
                $ownerModels = app(FragmentOwnerRepository::class)->getResourceOwners($model);

                foreach ($ownerModels as $ownerModel) {
                    $this->owners[] = $this->createOwnerFields($ownerModel, $model);
                }

                continue;
            }
            $this->owners[] = $this->createOwnerFields($model);
        }
    }

    // Find a matching owner by model reference
    public function findOwner(string $modelReference): ?array
    {
        foreach ($this->owners as $owner) {
            if ($owner['modelReference'] == $modelReference) {
                return $owner;
            }
        }

        return null;
    }

    private function createOwnerFields($resourceModel, ?FragmentModel $fragmentModel = null): array
    {
        if ($fragmentModel) {
            $fragment = app(FragmentFactory::class)->create($fragmentModel);
        }

        try {
            $resource = app(Registry::class)->findResourceByModel($resourceModel::class);
            $manager = app(Registry::class)->findManagerByModel($resourceModel::class);

            return [
                'label' => $resource->getPageTitle($resourceModel),
                'adminUrl' => $manager->route('edit', $resourceModel),
                'resourceModelReference' => $resourceModel->modelReference()->get(),
                'modelReference' => $resourceModel->modelReference()->get(),

                // If a fragmentModel is owner, we use this fragment as the real model reference.
                ...($fragmentModel) ? ['modelReference' => $fragmentModel->modelReference()->get()] : [],
                ...($fragmentModel) ? ['label' => $resource->getPageTitle($resourceModel).' > '.$fragment->getLabel()] : [],
            ];
        } catch (Exception $e) {
            report($e);
        }

        return ['label' => null, 'adminUrl' => null, 'resourceModelReference' => null, 'modelReference' => null];
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
