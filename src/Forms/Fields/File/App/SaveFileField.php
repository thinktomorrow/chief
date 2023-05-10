<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Thinktomorrow\AssetLibrary\Application\AddAsset;
use Thinktomorrow\AssetLibrary\Application\AssetUploader;
use Thinktomorrow\AssetLibrary\Application\SortAssets;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\File;

class SaveFileField
{
    protected AddAsset $addAsset;
    protected AssetUploader $assetUploader;
    private SortAssets $sortAssets;

    /** @var string the media disk where the files should be stored. */
    private $disk = '';

    final public function __construct(AddAsset $addAsset, SortAssets $sortAssets, AssetUploader $assetUploader)
    {
        $this->addAsset = $addAsset;
        $this->sortAssets = $sortAssets;
        $this->assetUploader = $assetUploader;
    }

    public function handle(HasAsset $model, File $field, array $input): void
    {
        if ($field->getStorageDisk()) {
            $this->setDisk($field->getStorageDisk());
        }

        // New

        // Deleted

        // Sort

        dd($input);
        foreach (data_get($input, 'files.'.$field->getName(), []) as $locale => $values) {
            $this->handlePayload($model, $field, $locale, $values);
        }

        //        $this->sort($model, $field, $input);
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

    private function handlePayload(HasAsset $model, File $field, string $locale, array $values): void
    {
        foreach ($values as $value) {
            $filename = $value['originalName'];
            $uploadedFile = new UploadedFile($value['path'], $filename, $value['mimeType']);

            $asset = $this->assetUploader->upload($uploadedFile, $filename, $this->getCollection(), $this->getDisk());

            $this->addAsset->add($model, $asset, $field->getKey(), $locale, $this->sluggifyFilename($filename), $this->getCollection(), $this->getDisk());
        }
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
}
