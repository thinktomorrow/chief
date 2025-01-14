<?php

namespace Thinktomorrow\Chief\Assets\App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\AssetLibrary\Application\ReorderAssets;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields\File;

class UpdateFileField
{
    protected AddAsset $addAsset;
    private CreateAsset $createAsset;
    private ReorderAssets $reorderAssets;
    private DetachAsset $detachAsset;

    /** @var string the media disk where the files should be stored. */
    private string $disk = '';

    /** @var string the asset type as which the asset is stored.. */
    private string $assetType = 'default';

    // Result log of the update process
    private array $result = [];

    final public function __construct(CreateAsset $createAsset, AddAsset $addAsset, DetachAsset $detachAsset, ReorderAssets $reorderAssets)
    {
        $this->createAsset = $createAsset;
        $this->addAsset = $addAsset;
        $this->reorderAssets = $reorderAssets;
        $this->detachAsset = $detachAsset;

        $this->result = [
            'uploaded' => [],
            'attached' => [],
            'detached' => [],
            'reordered' => [],
        ];
    }

    public function handle(HasAsset $model, File $field, array $input): array
    {
        if ($field->getStorageDisk()) {
            $this->setDisk($field->getStorageDisk());
        }
        if ($field->getAssetType()) {
            $this->setAssetType($field->getAssetType());
        }

        $existingAssets = $model->assetRelation()->get();

        foreach (data_get($input, 'files.' . $field->getName(), []) as $locale => $values) {

            $assetsForUpload = $values['uploads'] ?? [];
            $assetsForAttach = $values['attach'] ?? [];
            $assetsForDeletion = $values['queued_for_deletion'] ?? [];
            $assetsForOrder = collect($values['order'] ?? []);

            $this->handleUploads($model, $field, $locale, $assetsForUpload, $assetsForOrder);
            $this->handleAttach($model, $field, $locale, $assetsForAttach, $existingAssets->filter(fn ($asset) => $asset->pivot->type == $field->getKey() && $asset->pivot->locale == $locale)->pluck('id'));
            $this->handleDeletions($model, $field, $locale, $assetsForDeletion, $assetsForOrder);
            $this->handleReOrder($model, $field, $locale, $assetsForOrder);
        }

        return $this->result;
    }

    protected function getAssetType(): string
    {
        return $this->assetType;
    }

    protected function setAssetType(string $assetType): void
    {
        $this->assetType = $assetType;
    }

    private function handleUploads(HasAsset $model, File $field, string $locale, array $values, Collection &$orderedAssetIds): void
    {
        foreach ($values as $value) {
            $filename = $this->sluggifyFilename($value['originalName']);

            $uploadedFile = new UploadedFile($value['path'], $filename, $value['mimeType']);

            $asset = $this->createAsset
                ->uploadedFile($uploadedFile)
                ->filename($filename)
                ->save($this->getDisk(), $this->getAssetType());

            // Asset fields are not stored via this method - should be done after file is uploaded
            $this->addAsset->handle($model, $asset, $field->getKey(), $locale, 0, []);

            // Replace the temporary upload id with the asset id
            $orderedAssetIds = $orderedAssetIds->map(fn ($orderedAssetId) => $orderedAssetId == $value['id'] ? $asset->id : $orderedAssetId);

            if (isset($value['fieldValues'])) {
                app(FileApplication::class)->updateAssociatedAssetData(
                    $model->modelReference(),
                    $field->getKey(),
                    $locale,
                    $asset->id,
                    $value['fieldValues']
                );
            }

            $this->result['uploaded'][] = $asset->id;
        }
    }

    private function handleAttach(HasAsset $model, File $field, string $locale, array $values, Collection $existingAssetIds): void
    {
        // Avoid asset duplication
        foreach ($values as $orderIndex => $assetValues) {

            if ($existingAssetIds->contains($assetValues['id'])) {
                continue;
            }

            $this->addAsset->handle($model, Asset::find($assetValues['id']), $field->getKey(), $locale, $orderIndex, $assetValues['fieldValues'] ?? []);

            $this->result['attached'][] = $assetValues['id'];
        }
    }

    private function handleDeletions(HasAsset $model, File $field, string $locale, array $values, Collection &$orderedAssetIds): void
    {
        foreach ($values as $assetId) {
            $this->detachAsset->handle($model, $field->getKey(), $locale, [$assetId]);

            $orderedAssetIds = $orderedAssetIds->reject(fn ($orderedAssetId) => $orderedAssetId == $assetId);

            $this->result['detached'][] = $assetId;
        }
    }

    private function handleReOrder(HasAsset $model, File $field, string $locale, Collection $orderedAssetIds): void
    {
        $this->reorderAssets->handle($model, $field->getKey(), $locale, $orderedAssetIds->all());

        $this->result['reordered'][] = $orderedAssetIds->all();
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

    protected function getDisk(): string
    {
        return $this->disk;
    }

    protected function setDisk(string $disk): void
    {
        $this->disk = $disk;
    }
}
