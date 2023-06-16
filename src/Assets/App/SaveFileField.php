<?php

namespace Thinktomorrow\Chief\Assets\App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\AssetLibrary\Application\ReorderAssets;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Validation\ValidationParameters;

class SaveFileField
{
    private CreateAsset $createAsset;
    protected AddAsset $addAsset;
    protected AssetUploader $assetUploader;
    private ReorderAssets $reorderAssets;

    /** @var string the media disk where the files should be stored. */
    private $disk = '';
    private DetachAsset $detachAsset;

    final public function __construct(CreateAsset $createAsset, AddAsset $addAsset, DetachAsset $detachAsset, ReorderAssets $reorderAssets, AssetUploader $assetUploader)
    {
        $this->createAsset = $createAsset;
        $this->addAsset = $addAsset;
        $this->reorderAssets = $reorderAssets;
        $this->assetUploader = $assetUploader;
        $this->detachAsset = $detachAsset;
    }

    public function handle(HasAsset $model, File $field, array $input): void
    {
        if ($field->getStorageDisk()) {
            $this->setDisk($field->getStorageDisk());
        }

        foreach (data_get($input, 'files.' . $field->getName(), []) as $locale => $values) {
            $assetsForUpload = $values['uploads'] ?? [];
            $assetsForAttach = $values['attach'] ?? [];
            $assetsForDeletion = $values['queued_for_deletion'] ?? [];
            $assetsForOrder = collect($values['order'] ?? []);

            $this->handleUploads($model, $field, $locale, $assetsForUpload, $assetsForOrder);
            $this->handleAttach($model, $field, $locale, $assetsForAttach);
            $this->handleDeletions($model, $field, $locale, $assetsForDeletion, $assetsForOrder);
            $this->handleReOrder($model, $field, $locale, $assetsForOrder);
        }
    }

    private function handleUploads(HasAsset $model, File $field, string $locale, array $values, Collection &$orderedAssetIds): void
    {
        foreach ($values as $value) {
            $filename = $this->sluggifyFilename($value['originalName']);

            $uploadedFile = new UploadedFile($value['path'], $filename, $value['mimeType']);

            $asset = $this->createAsset
                ->uploadedFile($uploadedFile)
                ->filename($filename)
                ->save($this->getDisk());

            $this->addAsset->handle($model, $asset, $field->getKey(), $locale, 0, $value['fieldValues'] ?? []);

            // Replace the temporary upload id with the asset id
            $orderedAssetIds = $orderedAssetIds->map(fn ($orderedAssetId) => $orderedAssetId == $value['id'] ? $asset->id : $orderedAssetId);
        }
    }

    private function handleAttach(HasAsset $model, File $field, string $locale, array $values): void
    {
        foreach ($values as $orderIndex => $assetValues) {
            $this->addAsset->handle($model, Asset::find($assetValues['id']), $field->getKey(), $locale, $orderIndex, $assetValues['fieldValues'] ?? []);
        }
    }

    private function handleDeletions(HasAsset $model, File $field, string $locale, array $values, Collection &$orderedAssetIds): void
    {
        foreach ($values as $assetId) {
            $this->detachAsset->handle($model, $field->getKey(), $locale, [$assetId]);

            $orderedAssetIds = $orderedAssetIds->reject(fn ($orderedAssetId) => $orderedAssetId == $assetId);
        }
    }

    private function handleReOrder(HasAsset $model, File $field, string $locale, Collection $orderedAssetIds): void
    {
        $this->reorderAssets->handle($model, $field->getKey(), $locale, $orderedAssetIds->all());
    }

    private function sluggifyFilename(string $filename): string
    {
        if (false === strpos($filename, '.')) {
            return $filename;
        }

        $extension = substr($filename, strrpos($filename, '.') + 1);
        $filename = substr($filename, 0, strrpos($filename, '.'));

        return Str::slug($filename) . '.' . $extension;
    }

    protected function setDisk(string $disk): void
    {
        $this->disk = $disk;
    }

    protected function getDisk(): string
    {
        return $this->disk;
    }
}
