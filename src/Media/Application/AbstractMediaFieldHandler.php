<?php

declare(strict_types=1);

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
use Thinktomorrow\AssetLibrary\Application\SortAssets;

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

    /** @var SortAssets */
    protected $sortAssets;

    final public function __construct(AddAsset $addAsset, ReplaceAsset $replaceAsset, DetachAsset $detachAsset, SortAssets $sortAssets, AssetUploader $assetUploader)
    {
        $this->replaceAsset = $replaceAsset;
        $this->addAsset = $addAsset;
        $this->detachAsset = $detachAsset;
        $this->sortAssets = $sortAssets;
        $this->assetUploader = $assetUploader;
    }

    protected function mediaRequest(array $requests, MediaField $field, Request $request): MediaRequest
    {
        $mediaRequest = new MediaRequest();

        foreach ($requests as $requestData) {
            foreach ($requestData as $locale => $values) {
                foreach ($values as $key => $value) {

                    // The null entries in the 'replace' request are passed explicitly - the replace array contains all existing assets (ids as keys, null as value)
                    if (is_null($value)) {
                        $mediaRequest->add(MediaRequest::DETACH, new MediaRequestInput(
                            '', $locale, $field->getKey(), [
                                'existing_id'    => $key,
                                'value_as_assetid' => true,
                            ]
                        ));

                        continue;
                    }

                    $action = $this->looksLikeAnAssetID($key)
                        ? MediaRequest::REPLACE
                        : MediaRequest::NEW;

//                    // If the value is the same as the original key and the asset already exists, we'll ignore this request.
//                    if($key == $value && $keyRefersToExistingAsset && $valueRefersToExistingAsset) {
//                        continue;
//                    }

                    $mediaRequest->add($action, new MediaRequestInput(
                        $value, $locale, $field->getKey(), [
                            'existing_id'    => $key,
                            'value_as_assetid' => $this->looksLikeAnAssetID($value), // index key is used for replace method to indicate the current asset id
                        ]
                    ));

//                    // Slim can push the ajax response object as value so we'll need to extract the id from this object
//                    if(is_string($file) && ($slimPayload = json_decode($file)) && isset($slimPayload->id)) {
//                        $file = $slimPayload->id;
//                    }

//                    $mediaRequest->add($action, new MediaRequestInput(
//                        $file, $locale, $field->getKey(), [
//                            'index'          => $k,
//                            // index key is used for replace method to indicate the current asset id
//                            'value_as_assetid' => $this->refersToExistingAsset($file),
//                        ]
//                    ));
                }
            }
        }

        return $mediaRequest;
    }

    protected function looksLikeAnAssetID($value): bool
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
    protected function newExistingAsset(HasAsset $model, string $locale, string $type, $value): Asset
    {
        $existingAsset = Asset::find($value);

        if ($model->assetRelation()->where('asset_pivots.type', $type)->where('asset_pivots.locale', $locale)->get()->contains($existingAsset)) {
            throw new DuplicateAssetException();
        }

        return $this->addAsset->add($model, $existingAsset, $type, $locale);
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

    protected function sort(HasAsset $model, MediaField $field, Request $request)
    {
        if ($request->has('filesOrder')) {
            foreach ($request->input('filesOrder') as $locale => $fileIdInput) {
                $fileIds = $this->getFileIdsFromInput($field->getKey(), $fileIdInput);

                if (!empty($fileIds)) {
                    $this->sortAssets->handle($model, $fileIds, $field->getKey(), $locale);
                }
            }
        }
    }

    /**
     * @param string $key
     * @param array $fileIdInput
     * @return array
     */
    protected function getFileIdsFromInput(string $key, array $fileIdInput): array
    {
        $values = isset($fileIdInput[$key])
            ? $fileIdInput[$key]
            : (isset($fileIdInput['files-' . $key])
                ? $fileIdInput['files-' . $key]
                : ''
            );

        if (!$values) {
            return [];
        }

        return explode(',', $values);
    }
}
