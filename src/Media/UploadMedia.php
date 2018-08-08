<?php

namespace Thinktomorrow\Chief\Media;

use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Models\Asset;
use Thinktomorrow\AssetLibrary\Models\AssetUploader;

class UploadMedia
{
    /**
     * Upload from base64encoded files, usually
     * coming from slim upload component
     */
    public function fromUploadComponent(HasMedia $model, array $files_by_type, array $files_order_by_type)
    {
        // When no files are uploaded, we still would like to sort our assets duh
        if (empty($files_by_type)) {
            foreach ($files_order_by_type as $type => $files_order) {
                $model->sortFiles($type, explode(',', $files_order));
            }

            return;
        }

        // We allow for more memory consumption because the gd decoding can require a lot of memory when parsing large images.
        ini_set('memory_limit', '256M');

        foreach ($files_by_type as $type => $files) {
            $files_order = isset($files_order_by_type[$type]) ? explode(',', $files_order_by_type[$type]) : [];

            $this->addFiles($model, $type, $files, $files_order);
            $this->replaceFiles($model, $files);
            $this->removeFiles($model, $files);

            $model->sortFiles($type, $files_order);
        }
    }

    private function addFiles(HasMedia $model, string $type, array $files, array &$files_order)
    {
        if (isset($files['new']) && is_array($files['new']) && !empty($files['new'])) {
            foreach ($files['new'] as $file) {
                // new but removed files are passed as null, just leave them alone!
                if (!$file) {
                    continue;
                }

                $this->addFile($model, $type, $files_order, $file);
            }
        }
    }

    private function addFile(HasMedia $model, string $type, array &$files_order, $file)
    {
        if (is_string($file)) {
            $image_name = json_decode($file)->output->name;
            $asset = $this->addAsset(json_decode($file)->output->image, $type, null, $image_name, $model);
        } else {
            $image_name = $file->getClientOriginalName();
            $asset = $this->addAsset($file, $type, null, $image_name, $model);
        }

        // New files are passed with their filename (instead of their id)
        // For new files we will replace the filename with the id.
        if (false !== ($key = array_search($image_name, $files_order))) {
            $files_order[$key] = $asset->id;
        }
    }

    /**
     * Note: this is a replication of the AssetTrait::addFile() with the exception
     * that we want to return the asset in order to retrieve the id. This is
     * currently not available via the AssetTrait.
     */
    private function addAsset($file, $type = '', $locale = null, $filename = null, HasMedia $model)
    {
        $filename = $this->sluggifyFilename($filename);

        if (is_string($file)) {
            $asset = AssetUploader::uploadFromBase64($file, $filename);
        } else {
            $asset = AssetUploader::upload($file, $filename);
        }

        if ($asset instanceof Asset) {
            $asset->attachToModel($model, $type, $locale);
        }

        return $asset;
    }

    /**
     * @param HasMedia $model
     * @param array $files
     * @return array
     */
    private function replaceFiles(HasMedia $model, array $files)
    {
        if (isset($files['replace']) && is_array($files['replace']) && !empty($files['replace'])) {
            foreach ($files['replace'] as $id => $file) {
                // Existing files are passed as null, just leave them alone!
                if (!$file) {
                    continue;
                }

                $asset = AssetUploader::uploadFromBase64(json_decode($file)->output->image, json_decode($file)->output->name);
                $model->replaceAsset($id, $asset->id);
            }
        }
    }

    private function removeFiles(HasMedia $model, array $files)
    {
        if (isset($files['remove']) && is_array($files['remove']) && !empty($files['remove'])) {
            $model->assets()->whereIn('id', $files['remove'])->delete();
        }
    }

    /**
     * @param $filename
     * @return string
     */
    private function sluggifyFilename($filename): string
    {
        $extension = substr($filename, strrpos($filename, '.') + 1);
        $filename = substr($filename, 0, strrpos($filename, '.'));
        $filename = str_slug($filename) . '.' . $extension;

        return $filename;
    }
}
