<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Illuminate\Support\Arr;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Components\FilePreview;
use Thinktomorrow\Chief\Assets\Components\FileSelect;

class FilesComponent extends Component
{
    use WithFileUploads;

    public string $fieldName;
    public bool $allowMultiple = false;

    protected FilePreview $filePreview;
    protected FileSelect $fileSelect;
    protected array $components;

    public $listeners = [
        'upload:finished' => 'onUploadFinished',
        'assetUpdated' => 'onAssetUpdated',
        'assetsChosen' => 'onAssetsChosen',
    ];

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

    public function mount(string $fieldName, array $assets = [], array $components = [])
    {
        $this->fieldName = $fieldName;

        $this->previewFiles = array_map(fn (Asset $asset) => PreviewFile::fromAsset($asset), $assets);
        $this->components = array_map(fn (\Thinktomorrow\Chief\Forms\Fields\Component $component) => $component, $components);
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
        Arr::set($this->files, str_replace('files.', '', $key), TemporaryUploadedFile::createFromLivewire($value[0]));

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
    }

    public function onAssetsChosen(array $assetIds)
    {
        Asset::whereIn('id', $assetIds)->get()->each(function (Asset $asset) {
            $previewFile = PreviewFile::fromAsset($asset);
            $previewFile->isAttachedToModel = false;

            $this->previewFiles[] = $previewFile;
        });
    }

//    public function updatedFiles($value, $key): void
//    {
////        $this->validate([
////            'files.*' => 'email',
////        ]);
//return;
//        list($index, $attribute) = explode('.',$key);
//
//        // Each setting is individually passed (fileName, fileSize, fileRef). But we only want to update our
//        // previewFiles when the file is actually uploaded, e.g. when fileRef is updated.
//        if($attribute != 'fileRef') return;
//
//
//
//        $this->syncPreviewFiles();
//
//
//    }

    private function syncPreviewFiles()
    {
        // Livewire converts the public properties of PreviewFile object to an array. So we need to convert this back to an object
        $this->previewFiles = array_map(fn (array|PreviewFile $file) => $file instanceof PreviewFile ? $file : PreviewFile::fromArray($file), $this->previewFiles);

        foreach($this->files as $index => $newFileDetails) {

            if(! isset($newFileDetails['fileRef'])) {
                continue;
            }

            if(! is_null($index = $this->findPreviewFileIndex($newFileDetails['fileRef']->getFilename()))) {
                $this->previewFiles[$index] = PreviewFile::fromTemporaryUploadedFile($newFileDetails['fileRef'], $this->previewFiles[$index]);
            } else {
                $this->previewFiles[] = PreviewFile::fromTemporaryUploadedFile($newFileDetails['fileRef']);
            }

            //            dd($this->previewFiles);
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

    public function onAssetUpdated($fileId, array $values): void
    {
        // Immediately show the updated values
        $previewFile = $this->previewFiles[$this->findPreviewFileIndex($fileId)];
        $previewFile->filename = $values['basename'] . '.' . $previewFile->extension;
    }

//    public function getRules()
//    {
//        return ['email'];
//    }

//    public function rules(): array
//    {
//
//    }
//
//    public function messages(): array
//    {
//
//    }
//
//    public function validationAttributes(): array
//    {
//
//    }

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
