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
        // Parse request ...
        $mediaRequest = $this->mediaRequest([
            $request->file('files.' . $field->getName(), []),
            $request->input('files.' . $field->getName(), []),
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
        if ($mediaRequestInput->metadata('value_as_assetid')) {
            return $this->newExistingAsset($model, $mediaRequestInput);
        }

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $mediaRequestInput->value();

        $filename = $uploadedFile->getClientOriginalName();

        return $this->addAsset->add($model, $uploadedFile, $mediaRequestInput->type(), $mediaRequestInput->locale(), $this->sluggifyFilename($filename));
    }

    private function replace(HasAsset $model, MediaRequestInput $mediaRequestInput): Asset
    {
        $asset = $mediaRequestInput->metadata('value_as_assetid')
            ? $this->newExistingAsset($model, $mediaRequestInput)
            : $this->createNewAsset($model, $mediaRequestInput);

        $currentAssetId = $mediaRequestInput->metadata('existing_id');

        $this->replaceAsset->handle($model, $currentAssetId, $asset->id, $mediaRequestInput->type(), $mediaRequestInput->locale());

        return $asset;
    }

    private function detach(HasAsset $model, MediaRequestInput $mediaRequest)
    {
        $assetId = $mediaRequest->metadata('existing_id');

        $this->detachAsset->detach($model, $assetId, $mediaRequest->type(), $mediaRequest->locale());
    }

    protected function createNewAsset(HasAsset $model, MediaRequestInput $mediaRequestInput): Asset
    {
        if ($mediaRequestInput->metadata('value_as_assetid')) {
            return Asset::find($mediaRequestInput->value());
        }

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $mediaRequestInput->value();

        $filename = $uploadedFile->getClientOriginalName();

        return $this->assetUploader->upload($uploadedFile, $filename);
    }
}
