<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Thinktomorrow\Chief\Assets\Components\FilePreview;
use Thinktomorrow\Chief\Assets\Components\FileSelect;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;

trait FileUploadDefaults
{
    public string $fieldName;

    /**
     * For files that are not yet saved, we determine the expected asset type
     * by passing it to the PreviewFile along with the field upload component.
     * For existing assets, this is set when instantiating the PreviewFile.
     */
    public string $assetType = PreviewFile::DEFAULT_ASSETTYPE;

    public bool $allowMultiple = false;

    public array $acceptedMimeTypes = [];

    /**
     * The temporary uploaded files and the existing ones as previewFile object
     *
     * @var PreviewFile[]
     */
    public array $previewFiles = [];

    /**
     * The temporary uploaded files. These files
     * are not yet stored as media records.
     *
     * Each entry is an array of [id,fileName,fileRef]
     * FileRef is added as soon as the file is completely uploaded by Livewire
     */
    public $files = [];

    public array $rules = [];

    protected FilePreview $filePreview;

    protected FileSelect $fileSelect;

    protected array $components = [];

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
        return $this->getId().'-'.$this->fieldName;
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
     * @return void
     */
    public function onUploadFinished(string $name, array $tmpFilenames)
    {
        $fileKey = $name;
        $fileId = $this->getUploadFileIdByFileKey($fileKey);

        $this->validateUploadedFile($fileId, $temporaryUploadedFile = TemporaryUploadedFile::createFromLivewire($tmpFilenames[0]));

        // In case only one asset is allowed, we make sure to delete any existing / other uploads.
        if (! $this->allowMultiple) {
            foreach ($this->previewFiles as $previewFile) {

                // In subsequent uploads it occurs that previewFiles are synced before this listener. In that case we make sure
                // That current uploaded file is not wrongfully queued for deletion.
                if ($previewFile->id == $tmpFilenames[0]) {
                    continue;
                }

                $previewFile->isQueuedForDeletion = true;
            }
        }

        Arr::set($this->files, str_replace('files.', '', $fileKey), $temporaryUploadedFile);

        $this->syncPreviewFiles();

        $this->dispatchFilesUpdatedEvent();
        $this->dispatch('fileAdded');
    }

    private function getUploadFileIdByFileKey(string $fileKey): string
    {
        $index = $this->extractIndexFromFileKey($fileKey);

        return $this->files[$index]['id'];
    }

    /**
     * This extract the index from a dotted file key reference.
     * e.g. files.0.fileRef -> 0 (index)
     */
    private function extractIndexFromFileKey(string $fileKey): int
    {
        $fileKeyWithoutPrefix = substr($fileKey, strpos($fileKey, '.') + 1);

        return (int) substr($fileKeyWithoutPrefix, 0, strpos($fileKeyWithoutPrefix, '.'));
    }

    /**
     * Validation is performed for all fields
     * Each field is parsed for the proper validation rules and messages.
     */
    private function validateUploadedFile(string $fileId, TemporaryUploadedFile $uploadedFile): void
    {
        try {
            $validator = Validator::make(['files' => [$uploadedFile]], ['files' => $this->rules], [], ['files' => 'bestand']);
            $validator->validate();

            $this->setFilesValidatedState($fileId, true);
        } catch (ValidationException $e) {
            $this->setFilesValidatedState($fileId, false, $e->validator->errors()->first('files'));
            $this->removeUpload('files.'.$this->findUploadFileIndex($fileId), $uploadedFile->getFilename());
        }
    }

    private function setFilesValidatedState(string $fileId, bool $validatedState, ?string $validationMessage = null)
    {
        $file = $this->findUploadFile($fileId);
        $this->updateUploadFileValue($fileId, 'validated', $validatedState);

        $previewFile = isset($file['fileRef'])
            ? $this->findPreviewFile($file['fileRef']->getFileName())
            : $this->findPreviewFile($fileId);

        $previewFile->isValidated = $validatedState;
        $previewFile->validationMessage = $validationMessage;
    }

    public function findUploadFile($fileId): ?array
    {
        foreach ($this->files as $file) {
            if ($file['id'] == $fileId) {
                return $file;
            }
        }

        return null;
    }

    private function updateUploadFileValue($fileId, $key, $value): void
    {
        $index = $this->findUploadFileIndex($fileId);

        if (is_null($index)) {
            throw new InvalidArgumentException('No uploadFile found by id '.$fileId);
        }

        $this->files[$index][$key] = $value;
    }

    private function findUploadFileIndex($fileId): ?int
    {
        foreach ($this->files as $index => $fileArray) {
            if ($fileArray['id'] == $fileId) {
                return $index;
            }
        }

        return null;
    }

    private function findPreviewFile($id): ?PreviewFile
    {
        foreach ($this->previewFiles as $previewFile) {
            if ($previewFile->id == $id) {
                return $previewFile;
            }
        }

        return null;
    }

    private function syncPreviewFiles()
    {
        // Livewire converts the public properties of PreviewFile object to an array. So we need to convert this back to an object
        $this->previewFiles = array_map(fn (array|PreviewFile $file) => $file instanceof PreviewFile ? $file : PreviewFile::fromArray($file), $this->previewFiles);

        foreach ($this->files as $newFileDetails) {

            // This normally should not happen as the id is passed with the first upload call
            // However we've seen error issues reporting that id is missing so here we
            // are going to track it a bit to try to understand why id is missing.
            if (! isset($newFileDetails['id'])) {

                report(new InvalidArgumentException('id is unexpectedly missing... from file entry: '.print_r($newFileDetails, true)));

                continue;
            }

            /**
             * If the file is still uploading, we'll still add it to the previewFiles.
             * Once fully uploaded, this previewFile will be replaced by the fully uploaded file
             */
            if (! isset($newFileDetails['fileRef'])) {
                if (is_null($this->findPreviewFileIndex($newFileDetails['id']))) {
                    $this->previewFiles[] = PreviewFile::fromPendingUploadedFile($newFileDetails['id'], $newFileDetails['fileName'], $newFileDetails['fileSize']);
                }

                continue;
            }

            /**
             * From this point, we can assume the file is completely uploaded.
             */
            $uploadingIndex = $this->findPreviewFileIndex($newFileDetails['id']);

            if (! is_null($uploadingIndex) && $this->previewFiles[$uploadingIndex] && ! $this->previewFiles[$uploadingIndex]->isQueuedForDeletion) {
                $this->previewFiles[$uploadingIndex] = PreviewFile::fromTemporaryUploadedFile($newFileDetails['fileRef'], null, ['assetType' => $this->assetType]);
            }
        }
    }

    /**
     * The previewFile that has the uploadFileId as its id, is a pending previewFile and
     * once the file is fully uploaded, the temporaryUploadFile id will be used.
     *
     * @param  $fileId
     */
    private function findPreviewFileIndex($id): ?int
    {
        foreach ($this->previewFiles as $index => $previewFile) {
            if ($previewFile->id == $id) {
                return $index;
            }
        }

        return null;
    }

    public function onUploadErrored($name)
    {
        $fileId = $this->getUploadFileIdByFileKey($name);

        $this->findUploadFile($fileId)['validated'] = false;

        $previewFile = $this->findPreviewFile($fileId);
        $previewFile->isValidated = false;
        $previewFile->validationMessage = 'Bestand is niet opgeladen';
    }

    public function reorder($orderedIds)
    {
        $reorderedPreviewFiles = collect($orderedIds)
            ->map(fn ($previewFileId) => $this->previewFiles[$this->findPreviewFileIndex($previewFileId)])
            ->all();

        $this->previewFiles = $reorderedPreviewFiles;

        $this->dispatchFilesUpdatedEvent();
    }

    public function openFileEdit($fileId)
    {
        $this->emitDownTo('chief-wire::file-field-edit', 'open', ['previewfile' => $this->previewFiles[$this->findPreviewFileIndex($fileId)]]);
    }

    public function deleteFile($fileId)
    {
        foreach ($this->previewFiles as $file) {
            if ($file->id == $fileId) {

                $file->isQueuedForDeletion = true;

                $this->dispatchFilesUpdatedEvent();

                return;
            }
        }
    }

    public function undoDeleteFile($fileId)
    {
        foreach ($this->previewFiles as $file) {
            if ($file->id == $fileId) {
                $file->isQueuedForDeletion = false;

                $this->dispatchFilesUpdatedEvent();

                return;
            }
        }
    }

    public function onAssetUpdated(array $previewFileArray): void
    {
        $previewFile = PreviewFile::fromArray($previewFileArray);

        $this->previewFiles[$this->findPreviewFileIndex($previewFile->id)] = $previewFile;

        $this->dispatchFilesUpdatedEvent();
    }

    protected function dispatchFilesUpdatedEvent()
    {
        $this->dispatch('files-updated', ...[
            'fieldName' => $this->fieldName, // e.g. files[image][nl]
            'files' => $this->previewFiles,
            'parentComponentId' => $this->parentComponentId ?? null,
        ]);
    }
}
