<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields\Media\Application;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\AssetLibrary\Application\DetachAsset;
use Thinktomorrow\AssetLibrary\Application\ReplaceAsset;
use Thinktomorrow\AssetLibrary\Application\SortAssets;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Media\DuplicateAssetException;

class FileUpload
{
    use ChecksExistingAssets;

    protected ReplaceAsset $replaceAsset;
    protected AddAsset $addAsset;
    protected DetachAsset $detachAsset;
    protected AssetUploader $assetUploader;
    protected SortAssets $sortAssets;

    /** @var string the media disk where the files should be stored. */
    private $disk = '';

    final public function __construct(AddAsset $addAsset, ReplaceAsset $replaceAsset, DetachAsset $detachAsset, SortAssets $sortAssets, AssetUploader $assetUploader)
    {
        $this->replaceAsset = $replaceAsset;
        $this->addAsset = $addAsset;
        $this->detachAsset = $detachAsset;
        $this->sortAssets = $sortAssets;
        $this->assetUploader = $assetUploader;
    }

    public function handle(HasAsset $model, Field $field, array $input, array $files): void
    {
        if ($field->getStorageDisk()) {
            $this->setDisk($field->getStorageDisk());
        }

        foreach ([data_get($files, 'files.'.$field->getName(), []), data_get($input, 'files.'.$field->getName(), [])] as $requestPayload) {
            foreach ($requestPayload as $locale => $values) {
                $this->handlePayload($model, $field, $locale, $values);
            }
        }

        $this->sort($model, $field, $input);
    }

    /**
     * Default collection for the media records. - for the time
     * being this is not used in favor of the Asset types.
     */
    protected function getCollection(): string
    {
        return 'default';
    }

    protected function setDisk(string $disk): void
    {
        $this->disk = $disk;
    }

    protected function getDisk(): string
    {
        return $this->disk;
    }

    private function handlePayload(HasAsset $model, Field $field, string $locale, array $values): void
    {
        foreach ($values as $key => $value) {
            $keyIsAttachedAssetId = $this->isKeyAnAttachedAssetId($model->assetRelation, $locale, $field->getKey(), $key);

            if ($this->shouldNotBeProcessed($value, $key, $keyIsAttachedAssetId)) {
                continue;
            }

            /*
             * when value is null, it means that the asset is queued for detachment
             * if key isn't an attached asset reference, we ignore it because this
             * means that a newly uploaded asset was deleted in the same request
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

    private function new(HasAsset $model, string $locale, string $type, $value): Asset
    {
        $value = $this->prepareValue($value, function ($assetId) use ($model, $locale, $type) {
            return $this->newExistingAsset($model, $locale, $type, $assetId);
        });

        if ($value instanceof Asset) {
            return $value;
        }

        list($value, $filename) = $value;

        return $this->addAsset->add($model, $value, $type, $locale, $this->sluggifyFilename($filename), $this->getCollection(), $this->getDisk());
    }

    /** @return Asset */
    private function createNewAsset(HasAsset $model, string $locale, string $type, $value): Asset
    {
        $value = $this->prepareValue($value, function ($assetId) use ($model, $locale, $type) {
            return $this->newExistingAsset($model, $locale, $type, $assetId);
        });

        if ($value instanceof Asset) {
            return $value;
        }

        list($value, $filename) = $value;

        if ($value instanceof UploadedFile) {
            return $this->assetUploader->upload($value, $filename, $this->getCollection(), $this->getDisk());
        }

        return $this->assetUploader->uploadFromBase64($value, $filename, $this->getCollection(), $this->getDisk());
    }

    private function prepareValue($value, \Closure $addExistingAsset): array|Asset
    {
        if ($this->looksLikeAnAssetId($value)) {
            return call_user_func($addExistingAsset, $value);
        }

        if ($value instanceof UploadedFile) {
            $filename = $value->getClientOriginalName();
        } else {
            // Base64 encoded string
            $value = json_decode($value);

            // Slim can sometimes sent us the ajax upload response instead of the asset id. Let's make sure this is being dealt with.
            if (isset($value->id) && $this->looksLikeAnAssetId($value->id)) {
                return call_user_func($addExistingAsset, $value);
            }

            // Inputted value is expected to be a slim specific json string with output of base64.
            $filename = $value->output->name;
            $value = $value->output->image;
        }

        return [$value, $filename];
    }

    private function replace(HasAsset $model, string $locale, string $type, $currentAssetId, $value): Asset
    {
        $asset = $this->looksLikeAnAssetId($value)
            ? Asset::find($value)
            : $this->createNewAsset($model, $locale, $type, $value);

        $this->replaceAsset->handle($model, $currentAssetId, $asset->id, $type, $locale);

        return $asset;
    }

    private function detach(HasAsset $model, string $locale, string $type, $assetId): void
    {
        $this->detachAsset->detach($model, $assetId, $type, $locale);
    }

    private function shouldNotBeProcessed($value, $key, bool $keyIsAttachedAssetId): bool
    {
        // If value is a File instance than we allow to process it - this probably means we want to upload a new asset.
        if (is_object($value)) {
            return false;
        }

        // If the async upload is not finished yet and the user already uploads, the slim passes an "undefined" as value.
        if ('undefined' === $value) {
            return true;
        }

        // Passed id => id that are the same, refer to an already attached asset so skip this.
        if ($key == $value && $keyIsAttachedAssetId) {
            return true;
        }

        return false;
    }

    /**
     * @param $assetId
     *
     * @throws DuplicateAssetException
     */
    private function newExistingAsset(HasAsset $model, string $locale, string $type, $assetId): Asset
    {
        if ($model->assetRelation()
            ->where('asset_pivots.type', $type)
            ->where('asset_pivots.locale', $locale)
            ->where('assets.id', $assetId)
            ->exists()) {
            throw new DuplicateAssetException();
        }

        $existingAsset = Asset::find($assetId);

        return $this->addAsset->add($model, $existingAsset, $type, $locale, null, $this->getCollection(), $this->getDisk());
    }

    private function sluggifyFilename(string $filename): string
    {
        if (false === strpos($filename, '.')) {
            return $filename;
        }

        $extension = substr($filename, strrpos($filename, '.') + 1);
        $filename = substr($filename, 0, strrpos($filename, '.'));

        return Str::slug($filename).'.'.$extension;
    }

    private function sort(HasAsset $model, Field $field, array $input): void
    {
        $filesOrder = data_get($input, 'filesOrder', []);

        foreach ($filesOrder as $locale => $fileIdInput) {
            $fileIds = $this->getFileIdsFromInput($field->getKey(), $fileIdInput);

            if (! empty($fileIds)) {
                $this->sortAssets->handle($model, $fileIds, $field->getKey(), $locale);
            }
        }
    }

    private function getFileIdsFromInput(string $key, array $fileIdInput): array
    {
        $values = $fileIdInput[$key] ?? ($fileIdInput['files-'.$key] ?? '');

        if (! $values) {
            return [];
        }

        return explode(',', $values);
    }
}
