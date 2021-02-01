<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Media\Application;

use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\MediaField;

class ImageFieldHandler extends AbstractMediaFieldHandler
{
    public function handle(HasAsset $model, MediaField $field, array $input, array $files): void
    {
        foreach ([data_get($files, 'images.' . $field->getName(), []), data_get($input, 'images.' . $field->getName(), [])] as $requestPayload) {
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

        $value = json_decode($value);

        // Slim can sometimes sent us the ajax upload response instead of the asset id. Let's make sure this is being dealt with.
        if (isset($value->id) && $this->looksLikeAnAssetId($value->id)) {
            return $this->newExistingAsset($model, $locale, $type, $value->id);
        }

        // Inputted value is expected to be a slim specific json string with output of base64.
        $base64FileString = $value->output->image;

        $filename = $value->output->name;

        return $this->addAsset->add($model, $base64FileString, $type, $locale, $this->sluggifyFilename($filename));
    }

    protected function createNewAsset(HasAsset $model, string $locale, string $type, $value): Asset
    {
        $value = json_decode($value);

        // Slim can sometimes sent us the ajax upload response instead of the asset id. Let's make sure this is being dealt with.
        if (isset($value->id) && $this->looksLikeAnAssetId($value->id)) {
            return $this->newExistingAsset($model, $locale, $type, $value->id);
        }

        // Inputted value is expected to be a slim specific json string.
        $file = $value->output->image;

        $filename = $value->output->name;

        return $this->assetUploader->uploadFromBase64($file, $filename);
    }
}
