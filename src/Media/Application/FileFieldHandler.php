<?php declare(strict_types=1);

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
        $mediaRequest = $this->mediaRequest($field, $request);
trap($mediaRequest);
        foreach([MediaRequest::NEW, MediaRequest::REPLACE, MediaRequest::DETACH] as $action) {
            foreach($mediaRequest->getByKey($action) as $input) {
                $this->$action($model, $input);
            }
        }

        // TODO: sort the assets as well... perhaps can this be done in the mediaRequest class???

    }

    private function mediaRequest(MediaField $field, Request $request): MediaRequest
    {
        $mediaRequest = new MediaRequest();

        foreach($request->file('files.'.$field->getName(), []) as $locale => $filesPerLocale) {

            foreach($filesPerLocale as $action => $files) {
                foreach($files as $k => $file) {
                    $mediaRequest->add($action, new MediaRequestInput(
                        $file, $locale, $field->getKey(), ['index' => $k] // index key is used for replace method to indicate the current asset id
                    ));
                }
            }

        }

        return $mediaRequest;
    }

    private function new(HasAsset $model, MediaRequestInput $mediaRequest): Asset
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $mediaRequest->value();

        $filename = $uploadedFile->getClientOriginalName();

        return $this->addAsset->add($model, $uploadedFile, $mediaRequest->type(), $mediaRequest->locale(), $this->sluggifyFilename($filename));
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
}
