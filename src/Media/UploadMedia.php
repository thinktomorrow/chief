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
     *
     * @param HasMedia $model
     * @param array $files_by_type
     * @param array $files_order_by_type
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    public function fromUploadComponent(HasMedia $model, array $files_by_type, array $files_order_by_type)
    {
        ini_set('memory_limit', '256M');
        
        $files_by_type = $this->sanitizeFilesParameter($files_by_type);
        $files_order_by_type = $this->sanitizeFilesOrderParameter($files_order_by_type);
        $this->validateParameters($files_by_type, $files_order_by_type);

        // When no files are uploaded, we still would like to sort our assets duh
        if (empty($files_by_type)) {
            foreach ($files_order_by_type as $type => $files) {
                $this->sortFiles($model, $type, $files);
            }

            return;
        }

        foreach ($files_by_type as $type => $files_by_locale) {
            foreach ($files_by_locale as $locale => $files) {
                $this->validateFileUploads($files);
                
                $fileIdsCollection = $files_order_by_type[$type] ?? [];
                
                $this->addFiles($model, $type, $files, $fileIdsCollection, $locale);
                $this->replaceFiles($model, $files);
                $this->removeFiles($model, $files);
            }
            $this->sortFiles($model, $type, $fileIdsCollection ?? []);
        }
    }

    private function addFiles(HasMedia $model, string $type, array $files, array &$files_order, string $locale = null)
    {
        if (!$this->actionExists($files, 'new')) {
            return;
        }

        foreach ($files['new'] as $id => $file) {
            if (!$file) {
                continue;
            }

            $this->addFile($model, $type, $file, $files_order, $locale);
        }
    }

    /**
     * @param HasMedia $model
     * @param array $files
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     */
    private function replaceFiles(HasMedia $model, array $files)
    {
        if (!$this->actionExists($files, 'replace')) {
            return;
        }

        foreach ($files['replace'] as $id => $file) {
            if (!$file) {
                continue;
            }

            $asset = AssetUploader::uploadFromBase64(json_decode($file)->output->image, json_decode($file)->output->name);
            $model->replaceAsset($id, $asset->id);
        }
    }

    /**
     * @param HasMedia $model
     * @param array $files
     */
    private function removeFiles(HasMedia $model, array $files)
    {
        if (!$this->actionExists($files, 'delete')) {
            return;
        }

        foreach ($model->assets()->whereIn('id', $files['delete'])->get() as $asset) {
            $asset->delete();
        }
    }

    private function actionExists(array $files, string $action)
    {
        return (isset($files[$action]) && is_array($files[$action]) && !empty($files[$action]));
    }

    private function addFile(HasMedia $model, string $type, $file, array &$files_order, $locale = null)
    {
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
            $files_order[$key] = (string) $asset->id;
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

    private function validateParameters(array $files_by_type, array $files_order_by_type)
    {
        $actions = ['new', 'replace', 'delete'];
        foreach ($files_by_type as $type => $files) {
            foreach ($files as $locale => $_files) {
                if (!in_array($locale, config('translatable.locales'))) {
                    throw new \InvalidArgumentException('Corrupt file payload. key is expected to be a valid locale [' . implode(',', config('translatable.locales', [])). ']. Instead [' . $locale . '] is given.');
                }

                if (!is_array($_files)) {
                    throw new \InvalidArgumentException('A valid files entry should be an array of files, key with either [new, replace or delete]. Instead a ' . gettype($_files) . ' is given.');
                }

                foreach ($_files as $action => $file) {
                    if (!in_array($action, $actions)) {
                        throw new \InvalidArgumentException('A valid files entry should have a key of either ['.implode(',', $actions).']. Instead ' . $action . ' is given.');
                    }
                }
            }
        }
    }

    private function sanitizeFilesParameter(array $files_by_type): array
    {
        $defaultLocale = config('app.fallback_locale');

        foreach ($files_by_type as $type => $files) {
            foreach ($files as $locale => $_files) {
                if (!in_array($locale, config('translatable.locales'))) {
                    unset($files_by_type[$type][$locale]);

                    if (!isset($files_by_type[$type][$defaultLocale])) {
                        $files_by_type[$type][$defaultLocale] = [];
                    }

                    $files_by_type[$type][$defaultLocale][$locale] = $_files;
                }
            }
        }

        return $files_by_type;
    }

    private function sanitizeFilesOrderParameter(array $files_order_by_locale): array
    {
        foreach ($files_order_by_locale as $locale => $fileIdsCollection) {
            foreach ($fileIdsCollection as $type => $commaSeparatedFileIds) {
                $type = str_replace("files-", "", $type);
                $files_order_by_type[$type][] = explode(',', $commaSeparatedFileIds);
                $files_order_by_type[$type] = collect($files_order_by_type)->flatten()->unique()->toArray();
            }
        }

        return $files_order_by_type ?? $files_order_by_locale;
    }

    private function sortFiles(HasMedia $model, $type, array $sortedAssetIds)
    {
        $assets = $model->assets()->where('asset_pivots.type', $type)->get();

        foreach ($assets as $asset) {
            $pivot = $asset->pivot;
            $pivot->order = array_search($asset->id, $sortedAssetIds);

            $pivot->save();
        }
    }
}
