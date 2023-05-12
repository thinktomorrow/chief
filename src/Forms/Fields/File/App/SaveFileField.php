<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\AssetLibrary\Application\DeleteAsset;
use Thinktomorrow\AssetLibrary\Application\SortAssets;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields\File;

class SaveFileField
{
    protected AddAsset $addAsset;
    protected AssetUploader $assetUploader;
    private SortAssets $sortAssets;

    /** @var string the media disk where the files should be stored. */
    private $disk = '';
    private DeleteAsset $deleteAsset;

    final public function __construct(AddAsset $addAsset, DeleteAsset $deleteAsset, SortAssets $sortAssets, AssetUploader $assetUploader)
    {
        $this->addAsset = $addAsset;
        $this->sortAssets = $sortAssets;
        $this->assetUploader = $assetUploader;
        $this->deleteAsset = $deleteAsset;
    }

    public function handle(HasAsset $model, File $field, array $input): void
    {
        if ($field->getStorageDisk()) {
            $this->setDisk($field->getStorageDisk());
        }

        foreach (data_get($input, 'files.' . $field->getName(), []) as $locale => $values) {

            $assetsForUpload = $values['uploads'] ?? [];
            $assetsForDeletion = $values['queued_for_deletion'] ?? [];
            $assetsForOrder = collect($values['order'] ?? []);

            $this->handleUploads($model, $field, $locale, $assetsForUpload, $assetsForOrder);
            $this->handleDeletions($model, $field, $locale, $assetsForDeletion, $assetsForOrder);
            $this->handleReOrder($model, $field, $locale, $assetsForOrder);
        }
    }

    private function handleUploads(HasAsset $model, File $field, string $locale, array $values, Collection &$orderedAssetIds): void
    {
        foreach ($values as $value) {
            $filename = $value['originalName'];
            $uploadedFile = new UploadedFile($value['path'], $filename, $value['mimeType']);

            $asset = $this->assetUploader->upload($uploadedFile, $filename, 'default', $this->getDisk());

            $this->addAsset->add($model, $asset, $field->getKey(), $locale, $this->sluggifyFilename($filename), 'default', $this->getDisk()); // TODO: remove  'default'

            // Replace the temporary upload id with the asset id
            $orderedAssetIds = $orderedAssetIds->map(fn ($orderedAssetId) => $orderedAssetId == $value['id'] ? $asset->id : $orderedAssetId);
        }
    }

    private function handleDeletions(HasAsset $model, File $field, string $locale, array $values, Collection &$orderedAssetIds): void
    {
        foreach ($values as $value) {
            $this->deleteAsset->delete($value);

            $orderedAssetIds = $orderedAssetIds->reject(fn ($orderedAssetId) => $orderedAssetId == $value);
        }
    }

    private function handleReOrder(HasAsset $model, File $field, string $locale, Collection $orderedAssetIds): void
    {
        $this->sortAssets->handle($model, $orderedAssetIds->all(), $field->getKey(), $locale);
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
