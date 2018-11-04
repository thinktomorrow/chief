<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Media\UploadMedia;

trait ManagesMedia
{
    public function uploadMedia(Fields $fields, Request $request)
    {
        $files = array_merge($request->get('files', []), $request->file('files', []));
        $filesOrder = $request->get('filesOrder', []);

        app(UploadMedia::class)->fromUploadComponent($this->model, $files, $filesOrder);
    }

    protected function populateMedia(HasMedia $model): array
    {
        // Get all types for media
        $images = [];
        foreach ($this->fields()->all() as $field) {
            if ($field->ofType(FieldType::MEDIA)) {
                $images[$field->key] = [];
            }
        }

        foreach ($model->getAllFiles()->groupBy('pivot.type') as $type => $assetsByType) {
            foreach ($assetsByType as $asset) {
                $images[$type][] = (object)[
                    'id'       => $asset->id,
                    'filename' => $asset->getFilename(),
                    'url'      => $asset->getFileUrl(),
                ];
            }
        }

        return $images;
    }

    protected function populateDocuments(HasMedia $model): array
    {
        $documents = [];

        foreach ($this->fields()->all() as $field) {
            if ($field->ofType(FieldType::DOCUMENT)) {
                $documents[$field->key] = [];
            }
        }

        foreach ($model->getAllFiles()->groupBy('pivot.type') as $type => $assetsByType) {
            foreach ($assetsByType as $asset) {
                $documents[$type][] = $asset;
            }
        }

        return $documents;
    }
}
