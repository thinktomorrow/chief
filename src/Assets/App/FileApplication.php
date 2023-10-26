<?php

namespace Thinktomorrow\Chief\Assets\App;

use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\AssetLibrary\Application\ReplaceMedia;
use Thinktomorrow\AssetLibrary\Application\UpdateAssetData;
use Thinktomorrow\AssetLibrary\Application\UpdateAssociatedAssetData;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FileApplication
{
    private UpdateAssociatedAssetData $updateAssociatedAssetData;
    private CreateAsset $createAsset;
    private ReplaceMedia $replaceMedia;
    private UpdateAssetData $updateAssetData;
    private Registry $registry;

    public function __construct(Registry $registry, UpdateAssociatedAssetData $updateAssociatedAssetData, UpdateAssetData $updateAssetData, CreateAsset $createAsset, ReplaceMedia $replaceMedia)
    {
        $this->updateAssociatedAssetData = $updateAssociatedAssetData;
        $this->createAsset = $createAsset;
        $this->replaceMedia = $replaceMedia;
        $this->updateAssetData = $updateAssetData;
        $this->registry = $registry;
    }

    /**
     * Update the asset data associated with a specified model.
     */
    public function updateAssociatedAssetData(string $modelReference, string $fieldKey, string $locale, string $assetId, array $values): void
    {
        $model = ModelReference::fromString($modelReference)->instance();

        if ($model instanceof FragmentModel) {
            $model = $this->fragmentFactory($model);
        }

        $resource = $this->registry->findResourceByModel($model::class);

        // Split model specific values and generic ones
        $fieldKeys = array_map(fn ($field) => $field->getKey(), $resource->field($model, $fieldKey)->getComponents());
        $modelValues = Arr::only($values, $fieldKeys);
        $genericValues = Arr::except($values, $fieldKeys);

        if (count($modelValues) > 0) {
            $this->updateAssociatedAssetData->handle($model, $assetId, $fieldKey, $locale, $modelValues);
        }

        if (count($genericValues) > 0) {
            $this->updateAssetData($assetId, $genericValues);
        }
    }

    private function fragmentFactory(FragmentModel $fragmentModel): Fragmentable
    {
        return ModelReference::fromString($fragmentModel->model_reference)
            ->instance()
            ->setFragmentModel($fragmentModel);
    }

    /**
     * Update the generic asset data.
     */
    public function updateAssetData(string $assetId, array $values): void
    {
        $this->updateAssetData->handle($assetId, $values);
    }

    public function updateFileName(string $assetId, string $basename): void
    {
        $model = Asset::find($assetId)->getFirstMedia();

        // Strip extension should the user has entered extension
        $basename = basename($basename, '.' . $model->extension);

        $model->file_name = $basename . '.' . $model->extension;
        $model->save();
    }

    public function replaceMedia(string $assetId, UploadedFile $uploadedFile): void
    {
        /** @var Asset $existingAsset */
        $existingAsset = Asset::find($assetId);

        $newAsset = $this->createAsset
            ->uploadedFile($uploadedFile)
            ->save();

        $this->replaceMedia->handle($existingAsset->getFirstMedia(), $newAsset->getFirstMedia());

        $newAsset->delete();
    }

    public function replaceMediaByUrl(string $assetId, string $url): void
    {
        /** @var Asset $existingAsset */
        $existingAsset = Asset::find($assetId);

        $newAsset = $this->createAsset
            ->url($url)
            ->save();

        $this->replaceMedia->handle($existingAsset->getFirstMedia(), $newAsset->getFirstMedia());

        $newAsset->delete();
    }

    /**
     * Detaches the asset. This basically means that for the given model
     * the asset is duplicated as an isolated Asset.
     */
    public function isolateAsset(string $modelReference, string $fieldKey, string $locale, string $assetId): AssetContract
    {
        $model = ModelReference::fromString($modelReference)->instance();
        $existingAsset = $model->assets($fieldKey)->firstWhere(fn ($asset) => $asset->id == $assetId && $asset->pivot->locale == $locale);

        $newAsset = $this->createAsset
            ->path($existingAsset->getPath())
            ->save($existingAsset->getFirstMedia()->disk, $existingAsset->asset_type);

        $newAsset->data = $existingAsset->data;
        $newAsset->save();

        app(DetachAsset::class)->handle($model, $fieldKey, $locale, [$assetId]);
        app(AddAsset::class)->handle($model, $newAsset, $fieldKey, $locale, $existingAsset->pivot->order, $existingAsset->pivot->data);

        return $model->fresh()->assets($fieldKey)->firstWhere(fn ($asset) => $asset->id == $newAsset->id && $asset->pivot->locale == $locale);
    }
}
