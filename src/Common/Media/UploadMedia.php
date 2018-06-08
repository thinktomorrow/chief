<?php

namespace Thinktomorrow\Chief\Common\Media;

use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use Thinktomorrow\AssetLibrary\Models\Asset;
use Thinktomorrow\AssetLibrary\Models\AssetUploader;

class UploadMedia
{
    public function fromUploadComponent(HasMedia $model, array $files, array $files_order)
    {
        $this->addFiles($model, $files, $files_order);

        $this->replaceFiles($model, $files);

        $this->removeFiles($files);

        $model->sortFiles('files', $files_order);
    }

    /**
     * @param HasMedia $model
     * @param array $files
     * @return array
     */
    private function addFiles(HasMedia $model, array $files, array &$files_order): array
    {
        if (isset($files['new']) && is_array($files['new']) && !empty($files['new']))
        {
            foreach ($files['new'] as $file)
            {
                // new but removed files are passed as null, just leave them alone!
                if (!$file)
                {
                    continue;
                }

                $this->addFile($model, $files_order, $file);
            }
        }
    }

    /**
     * @param HasMedia $model
     * @param array $files_order
     * @param $file
     * @return array
     */
    private function addFile(HasMedia $model, array &$files_order, $file): array
    {
        $image_name = json_decode($file)->output->name;
        $asset = $this->addAsset(json_decode($file)->output->image, 'files', null, $image_name, $model);

        // New files are passed with their filename (instead of their id)
        // For new files we will replace the filename with the id.
        if (false !== ($key = array_search($image_name, $files_order)))
        {
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
        if(is_string($file))
        {
            $asset = AssetUploader::uploadFromBase64($file, $filename);
        }else{
            $asset = AssetUploader::upload($file, $filename);
        }

        if($asset instanceof Asset){
            $asset->attachToModel($model, $type, $locale);
        }

        return $asset;
    }

    /**
     * @param HasMedia $model
     * @param array $files
     * @return array
     */
    private function replaceFiles(HasMedia $model, array $files): array
    {
        if (isset($files['replace']) && is_array($files['replace']) && !empty($files['replace']))
        {
            foreach ($files['replace'] as $id => $file)
            {
                // Existing files are passed as null, just leave them alone!
                if (!$file)
                {
                    continue;
                }

                $asset = AssetUploader::uploadFromBase64(json_decode($file)->output->image, json_decode($file)->output->name);
                $model->replaceAsset($id, $asset->id);
            }

            return $files;
        }

        return $files;
    }

    /**
     * @param array $files
     */
    private function removeFiles(array $files)
    {
        if (isset($files['remove']) && is_array($files['remove']) && !empty($files['remove']))
        {
            Asset::remove($files['remove']);
        }
    }
}