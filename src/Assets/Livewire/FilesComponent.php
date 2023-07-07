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
use Thinktomorrow\Chief\Assets\Livewire\Traits\UploadsFieldFiles;

class FilesComponent extends Component
{
    use WithFileUploads;
    use UploadsFieldFiles;

    public ?string $modelReference;
    public string $fieldKey;
    public string $fieldName;
    public string $locale;
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
        $this->fileSelect = new FileSelect(
            $this->id, $this->fieldName, $this->previewFiles, $this->allowMultiple, $this->acceptedMimeTypes
        );

        $this->syncPreviewFiles();
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
        if(! $this->allowMultiple) {
            // Assert only one file is added.
            $assetIds = (array) reset($assetIds);

            foreach($this->previewFiles as $previewFile) {
                $previewFile->isQueuedForDeletion = true;
            }
        }

        Asset::whereIn('id', $assetIds)->get()->each(function (Asset $asset) {
            $previewFile = PreviewFile::fromAsset($asset);
            $previewFile->isAttachedToModel = false;

            $this->previewFiles[] = $previewFile;
        });
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
        $this->emitDownTo('chief-wire::attached-file-edit', 'open', ['previewfile' => $this->previewFiles[$this->findPreviewFileIndex($fileId)]]);
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
