<?php

namespace Thinktomorrow\Chief\Management;

use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\Chief\Common\Fields\FieldType;

trait ManagesMedia
{
    protected function populateMedia(HasMedia $model): array
    {
        // Get all types for media
        $images = [];
        foreach ($this->fields() as $field) {
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

        foreach ($this->fields() as $field) {
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
