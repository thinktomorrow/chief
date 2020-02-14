<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fields\Types\MediaField;

class ImageFieldHandler extends AbstractMediaFieldHandler
{
    public function handle(HasAsset $model, MediaField $field, Request $request): void
    {
        // Parse request ...
        $mediaRequest = $this->mediaRequest([
            $request->input('images.' . $field->getName(), []),
        ], $field, $request);

        foreach ([MediaRequest::NEW, MediaRequest::REPLACE, MediaRequest::DETACH] as $action) {
            foreach ($mediaRequest->getByKey($action) as $input) {
                $this->$action($model, $input);
            }
        }

        $this->sort($model, $field, $request);
    }

    private function new(HasAsset $model, MediaRequestInput $mediaRequestInput): Asset
    {
        if ($mediaRequestInput->metadata('existing_asset')) {
            return $this->newExistingAsset($model, $mediaRequestInput);
        }

        // Inputted value is expected to be a slim specific json string.
        $base64FileString = json_decode($mediaRequestInput->value())->output->image;

        $filename = json_decode($mediaRequestInput->value())->output->name;

        return $this->addAsset->add($model, $base64FileString, $mediaRequestInput->type(), $mediaRequestInput->locale(), $this->sluggifyFilename($filename));
    }

    private function replace(HasAsset $model, MediaRequestInput $mediaRequest): Asset
    {
        $asset = $this->createNewAsset($model, $mediaRequest);

        $currentAssetId = $mediaRequest->metadata('index');

        $this->replaceAsset->handle($model, $currentAssetId, $asset->id, $mediaRequest->type(), $mediaRequest->locale());

        return $asset;
    }

    private function detach(HasAsset $model, MediaRequestInput $mediaRequest)
    {
        $assetId = $mediaRequest->value();

        $this->detachAsset->detach($model, $assetId, $mediaRequest->type(), $mediaRequest->locale());
    }

    protected function createNewAsset(HasAsset $model, MediaRequestInput $mediaRequestInput): Asset
    {
        if ($mediaRequestInput->metadata('existing_asset')) {
            return Asset::find($mediaRequestInput->value());
        }

        // Inputted value is expected to be a slim specific json string.
        $file = json_decode($mediaRequestInput->value())->output->image;

        $filename = json_decode($mediaRequestInput->value())->output->name;

        return $this->assetUploader->uploadFromBase64($file, $filename);
    }
}
