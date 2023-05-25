<?php

namespace Thinktomorrow\Chief\Assets\App;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\AssetLibrary\Application\UpdateAssetData;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FileApplication
{
    private Registry $registry;
    private UpdateAssetData $updateAssetData;

    public function __construct(Registry $registry, UpdateAssetData $updateAssetData)
    {
        $this->registry = $registry;
        $this->updateAssetData = $updateAssetData;
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
        $model = Media::find($mediaId);

        // Strip extension should the user has entered extension
        $basename = basename($basename, '.'.$model->extension);

        $model->file_name = $basename . '.' . $model->extension;
        $model->save();
    }
}
