<?php

namespace Thinktomorrow\Chief\Management;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Fields;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Thinktomorrow\Chief\Media\UploadMedia;
use Thinktomorrow\Chief\Fields\Types\FieldType;

trait ManagesMedia
{
    public function uploadMedia(Fields $fields, Request $request)
    {
        $files = array_merge_recursive($request->get('files', []), $request->file('files', []));
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
        
        // There should be a function on assettrait to fetch all assets regardless of locale
        foreach ($model->assets->groupBy('pivot.type') as $type => $assetsByType) {
            foreach ($assetsByType as $asset) {
                $images[$type][] = (object)[
                    'id'       => $asset->id,
                    'filename' => $asset->getFilename(),
                    'url'      => $asset->getFileUrl(),
                    'locale'   => $asset->pivot->locale
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
