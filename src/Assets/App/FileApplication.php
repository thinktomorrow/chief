<?php

namespace Thinktomorrow\Chief\Assets\App;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Thinktomorrow\AssetLibrary\Application\CreateAsset;
use Thinktomorrow\AssetLibrary\Application\ReplaceMedia;
use Thinktomorrow\AssetLibrary\Application\UpdateAssetData;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FileApplication
{
    private Registry $registry;
    private UpdateAssetData $updateAssetData;
    private CreateAsset $createAsset;
    private ReplaceMedia $replaceMedia;

    public function __construct(Registry $registry, UpdateAssetData $updateAssetData, CreateAsset $createAsset, ReplaceMedia $replaceMedia)
    {
        $this->registry = $registry;
        $this->updateAssetData = $updateAssetData;
        $this->createAsset = $createAsset;
        $this->replaceMedia = $replaceMedia;
    }

    public function updateFieldValues(string $modelReference, string $fieldKey, string $locale, string $assetId, array $values): void
    {
        $resource = $this->registry->findResourceByModel(ModelReference::fromString($modelReference)->className());
        $model = ModelReference::fromString($modelReference)->instance();

        // Validate

        $this->updateAssetData->handle($model, $assetId, $fieldKey, $locale, $values);
    }

    public function updateFileName(string $mediaId, string $basename): void
    {
        $model = Asset::find($mediaId)->getFirstMedia();

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
    }
}
