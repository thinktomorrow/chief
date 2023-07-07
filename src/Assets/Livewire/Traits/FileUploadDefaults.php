<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Thinktomorrow\Chief\Assets\Components\FilePreview;
use Thinktomorrow\Chief\Assets\Components\FileSelect;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;

trait FileUploadDefaults
{
    use WithFileUploads;

    public string $fieldName;

    public bool $allowMultiple = false;
    public bool $isReordering = false;
    public array $acceptedMimeTypes = [];

    protected FilePreview $filePreview;
    protected FileSelect $fileSelect;
    protected array $components = [];

    /**
     * The temporary uploaded files and the existing ones as previewFile object
     *
     * @var PreviewFile[]
     */
    public $previewFiles = [];

    /**
     * The temporary uploaded files. These files
     * are not yet stored as media records.
     *
     * Each entry is an array of [id,fileName,fileRef]
     * FileRef is added as soon as the file is completely uploaded by Livewire
     */
    public $files = [];

    public array $rules = [];


    public function getPreviewFiles(): array
    {
        return $this->previewFiles;
    }

    public function areMultipleFilesAllowed(): bool
    {
        return $this->allowMultiple;
    }

    public function getFieldId(): string
    {
        return $this->id.'-'.$this->fieldName;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getAcceptedMimeTypes(): array
    {
        return $this->acceptedMimeTypes;
    }

    /**
     * After the upload of a file, we convert our passed
     * filepath to a valid UploadedFile object
     *
     * @param $key
     * @param $value
     * @return void
     */
    public function onUploadFinished($key, $value)
    {
        $this->validateUploadedFile($key, $temporaryUploadedFile = TemporaryUploadedFile::createFromLivewire($value[0]));

        // In case only one asset is allowed, we make sure to delete any existing / other uploads.
        if(! $this->allowMultiple) {
            foreach($this->previewFiles as $previewFile) {

                // In subsequent uploads it occurs that previewFiles are synced before this listener. In that case we make sure
                // That current uploaded file is not wrongfully queued for deletion.
                if($previewFile->id == $value[0]) {
                    continue;
                }

                $previewFile->isQueuedForDeletion = true;
            }
        }

        Arr::set($this->files, str_replace('files.', '', $key), $temporaryUploadedFile);

        $currentCount = count($this->previewFiles);
        $this->syncPreviewFiles();

        if(count($this->previewFiles) > $currentCount) {
            $this->emitSelf('fileAdded');
        }
    }

    private function syncPreviewFiles()
    {
        // Livewire converts the public properties of PreviewFile object to an array. So we need to convert this back to an object
        $this->previewFiles = array_map(fn (array|PreviewFile $file) => $file instanceof PreviewFile ? $file : PreviewFile::fromArray($file), $this->previewFiles);

        foreach($this->files as $newFileDetails) {

            // If the same file is already uploaded, we

            /**
             * If the file is still uploading, we'll still add it to the previewFiles.
             * Once fully uploaded, this previewFile will be replaced by the fully uploaded file
             */
            if(! isset($newFileDetails['fileRef'])) {
                if(is_null($this->findPreviewFileIndex($newFileDetails['id']))) {
                    $this->previewFiles[] = PreviewFile::fromPendingUploadedFile($newFileDetails['id'], $newFileDetails['fileName'], $newFileDetails['fileSize']);
                }

                continue;
            }

            /**
             * From this point, we can assume the file is completely uploaded.
             */
            $uploadingIndex = $this->findPreviewFileIndex($newFileDetails['id']);

            if(! is_null($uploadingIndex) && $this->previewFiles[$uploadingIndex]->isUploading) {
                $this->previewFiles[$uploadingIndex] = PreviewFile::fromTemporaryUploadedFile($newFileDetails['fileRef']);

            }

            /**
             * We can check here if the new upload is validated or not.
             */
            //            if(isset($newFileDetails['validated']) && ! $newFileDetails['validated']) {
            //                // Not validated
            //                // TODO: add validated state to each preview file to show which is not uploaded!
            //                continue;
            //                $this->previewFiles[$uploadingIndex]->isValidated = false;
            //            }
        }
    }

    public function findUploadingFileIndex($fileId): ?int
    {
        foreach($this->files as $index => $fileArray) {
            if($fileArray['id'] == $fileId) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateUploadedFile(string $key, TemporaryUploadedFile $uploadedFile): void
    {
        try {
            $validator = Validator::make(['files' => [$uploadedFile]], ['files' => $this->rules], [], ['files' => 'bestand']);
            $validator->validate();

            $this->setFilesValidatedState($key, true);
        } catch(ValidationException $e) {
            $this->setFilesValidatedState($key, false);
            $this->removeUpload($key, $uploadedFile->getFilename());

            throw $e;
        }

        $this->resetErrorBag();
    }

    private function setFilesValidatedState(string $key, bool $validatedState)
    {
        Arr::set($this->files, str_replace('files.', '', str_replace('fileRef', 'validated', $key)), $validatedState);
        // $this->previewFiles[$uploadingIndex]->isValidated = false;
    }

    public function reorder($orderedIds)
    {
        $reorderedPreviewFiles = collect($orderedIds)
            ->map(fn ($previewFileId) => $this->previewFiles[$this->findPreviewFileIndex($previewFileId)])
            ->all();

        $this->previewFiles = $reorderedPreviewFiles;

        $this->isReordering = false;
    }

    //    public function onAssetsChosen(array $assetIds)
    //    {
    //        if(! $this->allowMultiple) {
    //            // Assert only one file is added.
    //            $assetIds = (array) reset($assetIds);
    //
    //            foreach($this->previewFiles as $previewFile) {
    //                $previewFile->isQueuedForDeletion = true;
    //            }
    //        }
    //
    //        Asset::whereIn('id', $assetIds)->get()->each(function (Asset $asset) {
    //            $previewFile = PreviewFile::fromAsset($asset);
    //            $previewFile->isAttachedToModel = false;
    //
    //            $this->previewFiles[] = $previewFile;
    //        });
    //    }

    private function findPreviewFileIndex($fileId): ?int
    {
        foreach($this->previewFiles as $index => $previewFile) {
            if($previewFile->id == $fileId) {
                return $index;
            }
        }

        return null;
    }

    public function openFileEdit($fileId)
    {
        $this->emitDownTo('chief-wire::file-field-edit', 'open', ['previewfile' => $this->previewFiles[$this->findPreviewFileIndex($fileId)]]);
    }

    public function deleteFile($fileId)
    {
        foreach($this->previewFiles as $file) {
            if($file->id == $fileId) {
                $file->isQueuedForDeletion = true;

                return;
            }
        }
    }

    public function undoDeleteFile($fileId)
    {
        foreach($this->previewFiles as $file) {
            if($file->id == $fileId) {
                $file->isQueuedForDeletion = false;

                return;
            }
        }
    }

    public function onAssetUpdated(array $previewFileArray): void
    {
        $previewFile = PreviewFile::fromArray($previewFileArray);

        $this->previewFiles[$this->findPreviewFileIndex($previewFile->id)] = $previewFile;
    }

    private function emitDownTo($name, $event, array $params = [])
    {
        $this->emitTo($name, $event . '-' . $this->id, $params);
    }
}
