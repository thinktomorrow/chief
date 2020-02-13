<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Media\Application;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fields\Types\MediaField;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\Chief\Media\DuplicateAssetException;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\AssetLibrary\Application\ReplaceAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;

abstract class AbstractMediaFieldHandler
{
    /** @var ReplaceAsset */
    protected $replaceAsset;

    /** @var AddAsset */
    protected $addAsset;

    /** @var DetachAsset */
    protected $detachAsset;

    /** @var AssetUploader */
    protected $assetUploader;

    final public function __construct(AddAsset $addAsset, ReplaceAsset $replaceAsset, DetachAsset $detachAsset, AssetUploader $assetUploader)
    {
        $this->replaceAsset = $replaceAsset;
        $this->addAsset = $addAsset;
        $this->detachAsset = $detachAsset;
        $this->assetUploader = $assetUploader;
    }

    protected function mediaRequest(array $requests, MediaField $field, Request $request): MediaRequest
    {
        $mediaRequest = new MediaRequest();

        foreach ($requests as $requestData) {
            foreach ($requestData as $locale => $filesPerLocale) {
                foreach ($filesPerLocale as $action => $files) {

                    if (!is_array($files) || !in_array($action, [
                            MediaRequest::NEW,
                            MediaRequest::REPLACE,
                            MediaRequest::DETACH,
                        ])) {
                        throw new \InvalidArgumentException('Malformed request data. Files are expected to be passed in a localized array.');
                    }

                    foreach ($files as $k => $file) {

                        // The null entries in the 'replace' request are passed explicitly - the replace array contains all existing assets (ids as keys, null as value)
                        if (is_null($file)) {
                            continue;
                        }

                        $mediaRequest->add($action, new MediaRequestInput(
                            $file, $locale, $field->getKey(), [
                                'index' => $k,
                                // index key is used for replace method to indicate the current asset id
                                'existing_asset' => $this->refersToExistingAsset($file),
                            ]
                        ));
                    }
                }
            }
        }

        return $mediaRequest;
    }

    protected function refersToExistingAsset($value): bool
    {
        if (!is_string($value) && !is_int($value)) {
            return false;
        }

        // check if passed value is an ID
        return (bool)preg_match('/^[1-9][0-9]*$/', (string)$value);
    }

    /**
     * @param HasAsset $model
     * @param MediaRequestInput $mediaRequestInput
     * @return Asset
     * @throws DuplicateAssetException
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    protected function newExistingAsset(HasAsset $model, MediaRequestInput $mediaRequestInput): Asset
    {
        $existingAsset = Asset::find($mediaRequestInput->value());

        if ($model->assetRelation()->where('asset_pivots.type', $mediaRequestInput->type())->where('asset_pivots.locale', $mediaRequestInput->locale())->get()->contains($existingAsset)) {
            throw new DuplicateAssetException();
        }

        return $this->addAsset->add($model, $existingAsset, $mediaRequestInput->type(), $mediaRequestInput->locale());
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function sluggifyFilename(string $filename): string
    {
        if (false === strpos($filename, '.')) {
            return $filename;
        }

        $extension = substr($filename, strrpos($filename, '.') + 1);
        $filename = substr($filename, 0, strrpos($filename, '.'));

        return Str::slug($filename) . '.' . $extension;
    }
}
