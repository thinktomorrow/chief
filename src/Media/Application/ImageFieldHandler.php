<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fields\Types\MediaField;
use Thinktomorrow\Chief\Media\DuplicateAssetException;

class ImageFieldHandler extends AbstractMediaFieldHandler
{
    public function handle(HasAsset $model, MediaField $field, Request $request): void
    {
        // Parse request ...
        $mediaRequest = $this->mediaRequest([
            $request->input('images.'.$field->getName(), []),
        ], $field, $request);

        foreach([MediaRequest::NEW, MediaRequest::REPLACE, MediaRequest::DETACH] as $action) {
            foreach($mediaRequest->getByKey($action) as $input) {
                $this->$action($model, $input);
            }
        }

        // TODO: sort the assets as well... perhaps can this be done in the mediaRequest class???
    }

//    private function mediaRequest(MediaField $field, Request $request): MediaRequest
//    {
//        $mediaRequest = new MediaRequest();
//
//        foreach($request->input('images.'.$field->getName(), []) as $locale => $filesPerLocale) {
//
//            foreach($filesPerLocale as $action => $files) {
//                foreach($files as $k => $file) {
//
//                    // If the passed value is null, we do not want to process it.
//                    if(!$file) continue;
//
//                    $mediaRequest->add($action, new MediaRequestInput(
//                        $file, $locale, $field->getKey(), [
//                            'index' => $k,
//                            'existing_asset' => $this->refersToExistingAsset($file),
//                        ] // index key is used for e.g. replace method to indicate the current asset id
//                    ));
//                }
//            }
//
//        }
//
//        return $mediaRequest;
//    }

    private function new(HasAsset $model, MediaRequestInput $mediaRequestInput): Asset
    {
        if($mediaRequestInput->metadata('existing_asset')) {

            $existingAsset = Asset::find($mediaRequestInput->value());

            if ($model->assetRelation()->where('asset_pivots.type', $mediaRequestInput->type())->where('asset_pivots.locale', $mediaRequestInput->locale())->get()->contains($existingAsset)) {
                throw new DuplicateAssetException();
            }

            return $this->addAsset->add($model, $existingAsset, $mediaRequestInput->type(), $mediaRequestInput->locale());
        }

        // Inputted value is expected to be a slim specific json string.
        $base64FileString = json_decode($mediaRequestInput->value())->output->image;

        $filename = json_decode($mediaRequestInput->value())->output->name;

        return $this->addAsset->add($model, $base64FileString, $mediaRequestInput->type(), $mediaRequestInput->locale(), $this->sluggifyFilename($filename));
    }

    private function replace(HasAsset $model, MediaRequestInput $mediaRequest): Asset
    {
        $asset = $this->new($model, $mediaRequest);

        $currentAssetId = $mediaRequest->metadata('index');

        $this->replaceAsset->handle($model, $currentAssetId, $asset->id);

        return $asset;
    }

    private function detach(HasAsset $model, MediaRequestInput $mediaRequest)
    {
        $assetId = $mediaRequest->value();

        $this->detachAsset->detach($model, $assetId, $mediaRequest->type(), $mediaRequest->locale());
    }
}
