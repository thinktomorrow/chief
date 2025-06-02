<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory;
use Thinktomorrow\Chief\Assets\Components\FilePreview;
use Thinktomorrow\Chief\Assets\Components\FileSelect;
use Thinktomorrow\Chief\Assets\Livewire\Traits\EmitsToNestables;
use Thinktomorrow\Chief\Assets\Livewire\Traits\FileUploadDefaults;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithChoosingAssets;
use Thinktomorrow\Chief\Assets\Livewire\Traits\RenamesErrorBagFileAttribute;

class FileFieldUploadComponent extends Component implements HasPreviewFiles, HasSyncedFormInputs
{
    use EmitsToNestables;
    use FileUploadDefaults;
    use InteractsWithChoosingAssets;
    use RenamesErrorBagFileAttribute;
    use WithFileUploads;

    public ?string $modelReference;

    public string $fieldKey;

    public string $locale;

    public bool $allowExternalFiles = false;

    public bool $allowLocalFiles = true;

    public ?string $parentComponentId = null;

    public function mount(?string $modelReference, string $fieldKey, string $locale, string $fieldName, array $previewFiles, array $components = [])
    {
        $this->modelReference = $modelReference;
        $this->fieldKey = $fieldKey;
        $this->fieldName = $fieldName;
        $this->locale = $locale;

        // Assert instances of PreviewFile
        $this->previewFiles = array_map(fn (PreviewFile $previewFile) => $previewFile, $previewFiles);

        $this->components = array_map(fn (\Thinktomorrow\Chief\Forms\Fields\Component $component) => $component, $components);
    }

    public function getListeners()
    {
        return [
            'upload:finished' => 'onUploadFinished',
            'upload:errored' => 'onUploadErrored',
            'assetUpdated-'.$this->getId() => 'onAssetUpdated',
            'assetsChosen-'.$this->getId() => 'onAssetsChosen',
        ];
    }

    public function booted()
    {
        $this->filePreview = new FilePreview($this);
        $this->fileSelect = new FileSelect(
            $this,
            $this->allowLocalFiles,
            $this->allowLocalFiles,
            $this->allowExternalFiles && DriverFactory::any()
        );

        $this->syncPreviewFiles();
    }

    public function render()
    {
        $this->renameErrorBagFileAttribute();

        return view('chief-assets::file-field-upload', [
            //
        ]);
    }
}
