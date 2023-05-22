<?php

namespace Thinktomorrow\Chief\Assets\App;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Thinktomorrow\AssetLibrary\Asset;

class FileApplication
{
    public function updateFileValues(string $assetId, array $values): void
    {
        $model = Asset::find($assetId);

        $model->save();
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
