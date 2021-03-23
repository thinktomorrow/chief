<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fields\Types\MediaField;

class FileFieldHandler extends AbstractMediaFieldHandler
{
    public function handle(HasAsset $model, MediaField $field, Request $request): void
    {
        if($field->getStorageDisk()) {
            $this->setDisk($field->getStorageDisk());
        }

        foreach ([$request->file('files.' . $field->getName(), []), $request->input('files.' . $field->getName(), [])] as $requestPayload) {
            foreach ($requestPayload as $locale => $values) {
                $this->handlePayload($model, $field, $locale, $values);
            }
        }

        $this->sort($model, $field, $request);
    }

    protected function new(HasAsset $model, string $locale, string $type, $value): Asset
    {
        if ($this->looksLikeAnAssetId($value)) {
            return $this->newExistingAsset($model, $locale, $type, $value);
        }

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $value;

        $filename = $uploadedFile->getClientOriginalName();

        return $this->addAsset->add($model, $uploadedFile, $type, $locale, $this->sluggifyFilename($filename), $this->getCollection(), $this->getDisk());
    }

    protected function createNewAsset(HasAsset $model, string $locale, string $type, $value): Asset
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $value;

        $filename = $uploadedFile->getClientOriginalName();

        return $this->assetUploader->upload($uploadedFile, $filename, $this->getCollection(), $this->getDisk());
    }
}
