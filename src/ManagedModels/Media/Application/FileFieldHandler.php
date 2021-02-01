<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Media\Application;

use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\MediaField;

class FileFieldHandler extends AbstractMediaFieldHandler
{
    public function handle(HasAsset $model, MediaField $field, array $input, array $files): void
    {
        foreach ([data_get($files, 'files.' . $field->getName(), []), data_get($input, 'files.' . $field->getName(), [])] as $requestPayload) {
            foreach ($requestPayload as $locale => $values) {
                $this->handlePayload($model, $field, $locale, $values);
            }
        }

        $this->sort($model, $field, $input);
    }

    protected function new(HasAsset $model, string $locale, string $type, $value): Asset
    {
        if ($this->looksLikeAnAssetId($value)) {
            return $this->newExistingAsset($model, $locale, $type, $value);
        }

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $value;

        $filename = $uploadedFile->getClientOriginalName();

        return $this->addAsset->add($model, $uploadedFile, $type, $locale, $this->sluggifyFilename($filename));
    }

    protected function createNewAsset(HasAsset $model, string $locale, string $type, $value): Asset
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $value;

        $filename = $uploadedFile->getClientOriginalName();

        return $this->assetUploader->upload($uploadedFile, $filename);
    }
}
