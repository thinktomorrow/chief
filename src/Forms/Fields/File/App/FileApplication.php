<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\App;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileApplication
{
    public function updateFileName(string $mediaId, string $basename): void
    {
        $model = Media::find($mediaId);

        // Strip extension should the user has entered extension
        $basename = basename($basename, '.'.$model->extension);

        $model->file_name = $basename . '.' . $model->extension;
        $model->save();
    }
}
