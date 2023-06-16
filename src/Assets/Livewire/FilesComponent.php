<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Components\FilePreview;
use Thinktomorrow\Chief\Assets\Components\FileSelect;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\File;

class FilesComponent extends Component
{
    use WithFileUploads;

    public ?string $modelReference;
    public string $fieldKey;
    public string $fieldName;
    public string $locale;
    public bool $allowMultiple = false;
    public bool $isReordering = false;
    public array $rules = [];
    public array $validationMessages = [];
    public ?string $validationAttribute = null;
    public array $acceptedMimeTypes = [];

    protected FilePreview $filePreview;
    protected FileSelect $fileSelect;
    protected array $components = [];

    /**
     * The temporary uploaded files. These files
     * are not yet stored as media records.
     *
     * @var TemporaryUploadedFile[]
     */
    public $files = [];

    /**
     * The temporary uploaded files and the existing ones as previewFile object
     *
     * @var PreviewFile[]
     */
    public $previewFiles = [];

    public function mount(?string $modelReference, string $fieldKey, string $fieldName, string $locale, array $assets = [], array $components = [])
    {
        $this->modelReference = $modelReference;
        $this->fieldKey = $fieldKey;
        $this->fieldName = $fieldName;
        $this->locale = $locale;

        $this->previewFiles = array_map(fn (Asset $asset) => PreviewFile::fromAsset($asset), $assets);
        $this->components = array_map(fn (\Thinktomorrow\Chief\Forms\Fields\Component $component) => $component, $components);
    }

    public function getListeners()
    {
        return [
            'upload:finished' => 'onUploadFinished',
            'assetUpdated' => 'onAssetUpdated',
            'assetsChosen-'.$this->id => 'onAssetsChosen',
        ];
    }

    public function booted()
    {
        $this->filePreview = new FilePreview($this);
        $this->fileSelect = new FileSelect($this);

        $this->syncPreviewFiles();
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
        if(!$this->allowMultiple) {
            foreach($this->previewFiles as $previewFile) {

                // In subsequent uploads it occurs that previewFiles are synced before this listener. In that case we make sure
                // That current uploaded file is not wrongfully queued for deletion.
                if($previewFile->id == $value[0]) continue;

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

    public function reorder($orderedIds)
    {
        $reorderedPreviewFiles = collect($orderedIds)
            ->map(fn ($previewFileId) => $this->previewFiles[$this->findPreviewFileIndex($previewFileId)])
            ->all();

        $this->previewFiles = $reorderedPreviewFiles;

        $this->isReordering = false;
    }

    public function onAssetsChosen(array $assetIds)
    {
        if(!$this->allowMultiple) {
            // Assert only one file is added.
            $assetIds = (array) reset($assetIds);

            foreach($this->previewFiles as $previewFile) {
//                if(in_array($previewFile->id, $assetIds)) {
////                    dd($previewFile);
//                }

                $previewFile->isQueuedForDeletion = true;
            }
        }

        Asset::whereIn('id', $assetIds)->get()->each(function (Asset $asset) {
            $previewFile = PreviewFile::fromAsset($asset);
            $previewFile->isAttachedToModel = false;

            $this->previewFiles[] = $previewFile;
        });
    }

    private function syncPreviewFiles()
    {
        // Livewire converts the public properties of PreviewFile object to an array. So we need to convert this back to an object
        $this->previewFiles = array_map(fn (array|PreviewFile $file) => $file instanceof PreviewFile ? $file : PreviewFile::fromArray($file), $this->previewFiles);

        foreach($this->files as $newFileDetails) {

            // Only proceed if the temp upload has completed
            if(! isset($newFileDetails['fileRef'])) {
                continue;
            }

            // Only add to files is upload is valid
            if(! isset($newFileDetails['validated']) || ! $newFileDetails['validated']) {
                continue;
            }

            if(is_null($this->findPreviewFileIndex($newFileDetails['fileRef']->getFilename()))) {
                $this->previewFiles[] = PreviewFile::fromTemporaryUploadedFile($newFileDetails['fileRef']);
            }
        }
    }

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
        $this->emitDownTo('chief-wire::file-edit', 'open', ['previewfile' => $this->previewFiles[$this->findPreviewFileIndex($fileId)]]);
    }

    public function openFilesChoose()
    {
        $this->emitDownTo('chief-wire::files-choose', 'open');
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

        //        $previewFile->filename = $values['basename'] . '.' . $previewFile->extension;
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
    }

    public function render()
    {
        return view('chief-assets::files-component', [
            //
        ]);
    }

    private function emitDownTo($name, $event, array $params = [])
    {
        $this->emitTo($name, $event . '-' . $this->id, $params);
    }
}
