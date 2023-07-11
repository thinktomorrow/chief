<?php

namespace Thinktomorrow\Chief\Assets\App;

use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Application\ReplaceMedia;
use Thinktomorrow\AssetLibrary\Application\UpdateAssetData;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FileApplication
{
    private UpdateAssetData $updateAssetData;
    private CreateAsset $createAsset;
    private ReplaceMedia $replaceMedia;

    public function __construct(UpdateAssetData $updateAssetData, CreateAsset $createAsset, ReplaceMedia $replaceMedia)
    {
        $this->updateAssetData = $updateAssetData;
        $this->createAsset = $createAsset;
        $this->replaceMedia = $replaceMedia;
    }

    public function updateFieldValues(string $modelReference, string $fieldKey, string $locale, string $assetId, array $values): void
    {
        $model = ModelReference::fromString($modelReference)->instance();

        // Validate

        $this->updateAssetData->handle($model, $assetId, $fieldKey, $locale, $values);
    }

    public function updateFileName(string $assetId, string $basename): void
    {
        $model = Asset::find($assetId)->getFirstMedia();

        // Strip extension should the user has entered extension
        $basename = basename($basename, '.'.$model->extension);

        $model->file_name = $basename . '.' . $model->extension;
        $model->save();
    }

    public function replaceMedia(string $assetId, \Symfony\Component\HttpFoundation\File\UploadedFile $uploadedFile): void
    {
        /** @var Asset $existingAsset */
        $existingAsset = Asset::find($assetId);

        $newAsset = $this->createAsset
                ->uploadedFile($uploadedFile)
                ->save();

        $this->replaceMedia->handle($existingAsset->getFirstMedia(), $newAsset->getFirstMedia());

        $newAsset->delete();
    }
}
