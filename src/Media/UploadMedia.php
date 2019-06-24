<?php

namespace Thinktomorrow\Chief\Media;

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\HasMedia\HasMedia;
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
            $this->validateFileUploads($files);

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
        $locale = null;
        if(strpos($type, '.') !== FALSE ){
            $locale = substr($type, 6, 2);
            $type = substr($type, 9);   
        }

        if (is_string($file)) {
            $image_name = json_decode($file)->output->name;
            $asset      = $this->addAsset(json_decode($file)->output->image, $type, $locale, $image_name, $model);
        } else {
            $image_name = $file->getClientOriginalName();
            $asset      = $this->addAsset($file, $type, $locale, $image_name, $model);
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
        if (isset($files['delete']) && is_array($files['delete']) && !empty($files['delete'])) {
            foreach($model->assets()->whereIn('id', $files['delete'])->get() as $asset){
                $asset->delete();
            }
        }
    }

    /**
     * @param $filename
     * @return string
     */
    private function sluggifyFilename($filename): string
    {
        $extension = substr($filename, strrpos($filename, '.') + 1);
        $filename  = substr($filename, 0, strrpos($filename, '.'));
        $filename  = str_slug($filename) . '.' . $extension;

        return $filename;
    }

    /**
     * @param $files
     * @throws FileTooBigException
     */
    private function validateFileUploads($files): void
    {
        foreach ($files as $_files) {
            foreach ($_files as $file) {
                if ($file instanceof UploadedFile && !$file->isValid()) {
                    if ($file->getError() == UPLOAD_ERR_INI_SIZE) {
                        throw new FileTooBigException(
                            'Cannot upload file because it exceeded the allowed upload_max_filesize: upload_max_filesize is smaller than post size. ' .
                            'upload_max_filesize: ' . (int)ini_get('upload_max_filesize') . 'MB, ' .
                            'post_max_size: ' . (int)(ini_get('post_max_size')) . 'MB'
                        );
                    }
                }
            }
        }
    }
}
