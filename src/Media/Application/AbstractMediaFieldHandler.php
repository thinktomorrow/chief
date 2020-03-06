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
    use ChecksExistingAssets;

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

    protected function handlePayload(HasAsset $model, MediaField $field, string $locale, $values)
    {
        foreach ($values as $key => $value) {
            $keyIsAttachedAssetId = $this->isKeyAnAttachedAssetId($model->assetRelation, $locale, $field->getKey(), $key);

            if ($this->shouldNotBeProcessed($value, $key, $keyIsAttachedAssetId)) {
                continue;
            }

            /*
             * when value is null, it means that the asset is queued for detachment
             * is key isn't an attached asset reference, we ignore it because this
             * means that a newly uploaded asses is deleted in the same request
             */
            if (is_null($value)) {
                if ($keyIsAttachedAssetId) {
                    $this->detach($model, $locale, $field->getKey(), $key);
                }

                continue;
            }

            // If key refers to an already existing asset, it is queued for replacement by a new one
            if ($keyIsAttachedAssetId) {
                $this->replace($model, $locale, $field->getKey(), $key, $value);
                continue;
            }

            $this->new($model, $locale, $field->getKey(), $value);
        }
    }

    abstract protected function new(HasAsset $model, string $locale, string $type, $value): Asset;

    protected function replace(HasAsset $model, string $locale, string $type, $currentAssetId, $value): Asset
    {
        $asset = $this->looksLikeAnAssetId($value)
            ? Asset::find($value)
            : $this->createNewAsset($model, $locale, $type, $value);

        $this->replaceAsset->handle($model, $currentAssetId, $asset->id, $type, $locale);

        return $asset;
    }

    protected function detach(HasAsset $model, string $locale, string $type, $assetId)
    {
        $this->detachAsset->detach($model, $assetId, $type, $locale);
    }

    abstract protected function createNewAsset(HasAsset $model, string $locale, string $type, $value): Asset;

    protected function shouldNotBeProcessed($value, $key, bool $keyIsAttachedAssetId): bool
    {
        // If the async upload is not finished yet and the user already uploads, the slim passes an "undefined" as value.
        if ($value === "undefined") {
            return true;
        }

        // Passed id => id that are the same, refer to an already attached asset so skip this.
        if ($key == $value && $keyIsAttachedAssetId) {
            return true;
        }

        return false;
    }

    /**
     * @param HasAsset $model
     * @param string $locale
     * @param string $type
     * @param $assetId
     * @return Asset
     * @throws DuplicateAssetException
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    protected function newExistingAsset(HasAsset $model, string $locale, string $type, $assetId): Asset
    {
        $existingAsset = Asset::find($assetId);

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
    private function getFileIdsFromInput(string $key, array $fileIdInput): array
    {
        $values = isset($fileIdInput[$key])
            ? $fileIdInput[$key]
            : (
                isset($fileIdInput['files-' . $key])
                ? $fileIdInput['files-' . $key]
                : ''
            );

        if (!$values) {
            return [];
        }

        return explode(',', $values);
    }
}
