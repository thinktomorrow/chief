<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fields\Types\MediaField;

class ImageFieldHandler extends AbstractMediaFieldHandler
{
    public function handle(HasAsset $model, MediaField $field, Request $request): void
    {
        $existingAttactedAssets = $model->assetRelation;

        foreach($request->input('images.' . $field->getName(), []) as $locale => $values) {
            foreach ($values as $key => $value) {

                // value is null ?
                if (is_null($value)) {
                    $this->detach($model, $value);
                    continue;
                }

                // Does key refer to existing asset? -> replace
                $keyRefersToAttachedAsset = $existingAttactedAssets
                    ->where('pivot.locale', $locale)
                    ->where('pivot.type', $field->getKey())
                    ->where('pivot.entity_id', $key)
                    ->count();

                if($keyRefersToAttachedAsset) {
                    $this->replace($model, new MediaRequestInput($value, $locale, $field->getKey()));
                }

                trap($key, $value, $existingAttactedAssets, $keyRefersToAttachedAsset);

            }

        }

        // Parse request ...
        $mediaRequest = $this->mediaRequest([
            $request->input('images.' . $field->getName(), []),
        ], $field, $request);
trap($request->all(),$mediaRequest);
        foreach ([MediaRequest::NEW, MediaRequest::REPLACE, MediaRequest::DETACH] as $action) {
            foreach ($mediaRequest->getByKey($action) as $input) {
                $this->$action($model, $input);
            }
        }

        $this->sort($model, $field, $request);
    }

    private function new(HasAsset $model, MediaRequestInput $mediaRequestInput): Asset
    {
        if ($mediaRequestInput->metadata('value_as_assetid')) {
            return $this->newExistingAsset($model, $mediaRequestInput);
        }

        // Inputted value is expected to be a slim specific json string.
        $base64FileString = json_decode($mediaRequestInput->value())->output->image;

        $filename = json_decode($mediaRequestInput->value())->output->name;

        return $this->addAsset->add($model, $base64FileString, $mediaRequestInput->type(), $mediaRequestInput->locale(), $this->sluggifyFilename($filename));
    }

    private function replace(HasAsset $model, string $locale, string $type, $value): Asset
    {
        $asset = $this->looksLikeAnAssetID($value)
            ? $this->newExistingAsset($model, $locale, $type, $value)
            : $this->createNewAsset($model, $locale, $type, $value);

        $currentAssetId = $mediaRequestInput->metadata('existing_id');

        $this->replaceAsset->handle($model, $currentAssetId, $asset->id, $mediaRequestInput->type(), $mediaRequestInput->locale());

        return $asset;
    }

    private function detach(HasAsset $model, MediaRequestInput $mediaRequest)
    {
        $assetId = $mediaRequest->metadata('existing_id');

        $this->detachAsset->detach($model, $assetId, $mediaRequest->type(), $mediaRequest->locale());
    }

    protected function createNewAsset(HasAsset $model, $value): Asset
    {
        if($this->looksLikeAnAssetID($value)) {
            return Asset::find($value);
        }

        // Inputted value is expected to be a slim specific json string.
        $file = json_decode($value)->output->image;

        $filename = json_decode($value)->output->name;

        return $this->assetUploader->uploadFromBase64($file, $filename);
    }
}
