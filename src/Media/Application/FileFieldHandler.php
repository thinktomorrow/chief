<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fields\Types\MediaField;
use Thinktomorrow\Chief\Media\DuplicateAssetException;

class FileFieldHandler extends AbstractMediaFieldHandler
{
    public function handle(HasAsset $model, MediaField $field, Request $request): void
    {
        // Parse request ...
        $mediaRequest = $this->mediaRequest([
            $request->file('files.'.$field->getName(), []),
            $request->input('files.'.$field->getName(), []),
        ], $field, $request);

        foreach([MediaRequest::NEW, MediaRequest::REPLACE, MediaRequest::DETACH] as $action) {
            foreach($mediaRequest->getByKey($action) as $input) {
                $this->$action($model, $input);
            }
        }

        // TODO: sort the assets as well... perhaps can this be done in the mediaRequest class???

    }

    private function new(HasAsset $model, MediaRequestInput $mediaRequestInput): Asset
    {
        if($mediaRequestInput->metadata('existing_asset')) {
            return $this->newExistingAsset($model, $mediaRequestInput);
        }

        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $mediaRequestInput->value();

        $filename = $uploadedFile->getClientOriginalName();

        return $this->addAsset->add($model, $uploadedFile, $mediaRequestInput->type(), $mediaRequestInput->locale(), $this->sluggifyFilename($filename));
    }

    private function replace(HasAsset $model, MediaRequestInput $mediaRequest): Asset
    {
        $asset = $this->add($model, $mediaRequest);

        $currentAssetId = $mediaRequest->metadata('index');

        $this->replaceAsset->handle($model, $currentAssetId, $asset->id);
    }

    private function detach(HasAsset $model, MediaRequestInput $mediaRequest)
    {
        $assetId = $mediaRequest->value();

        $this->detachAsset->detach($model, $assetId, $mediaRequest->type(), $mediaRequest->locale());
    }

//    protected function createNewAsset(HasAsset $model, MediaRequestInput $mediaRequestInput): Asset
//    {
//        if($mediaRequestInput->metadata('existing_asset')) {
//            return Asset::find($mediaRequestInput->value());
//        }
//
//        // Inputted value is expected to be a slim specific json string.
//        $file = json_decode($mediaRequestInput->value())->output->image;
//
//        $filename = json_decode($mediaRequestInput->value())->output->name;
//
//        return $this->assetUploader->uploadFromBase64($file, $filename);
//    }
}
