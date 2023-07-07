<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Components\FilePreview;
use Thinktomorrow\Chief\Assets\Components\FileSelect;
use Thinktomorrow\Chief\Assets\Livewire\Traits\FileUploadDefaults;

class FileFieldUploadComponent extends Component implements HasPreviewFiles, HasSyncedFormInputs
{
    use WithFileUploads;
    use FileUploadDefaults;

    public ?string $modelReference;
    public string $fieldKey;
    public string $locale;

    public function mount(?string $modelReference, string $fieldKey, string $locale, string $fieldName, array $assets = [], array $components = [])
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

    public function openFilesChoose()
    {
        $this->emitDownTo('chief-wire::file-field-choose', 'open');
    }

    public function render()
    {
        return view('chief-assets::file-field-upload', [
            //
        ]);
    }
}
