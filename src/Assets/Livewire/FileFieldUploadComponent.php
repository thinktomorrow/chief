<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Thinktomorrow\AssetLibrary\AssetContract;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory;
use Thinktomorrow\Chief\Assets\Components\FilePreview;
use Thinktomorrow\Chief\Assets\Components\FileSelect;
use Thinktomorrow\Chief\Assets\Livewire\Traits\FileUploadDefaults;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithChoosingAssets;
use Thinktomorrow\Chief\Assets\Livewire\Traits\RenamesErrorBagFileAttribute;

class FileFieldUploadComponent extends Component implements HasPreviewFiles, HasSyncedFormInputs
{
    use WithFileUploads;
    use FileUploadDefaults;
    use InteractsWithChoosingAssets;
    use RenamesErrorBagFileAttribute;

    public ?string $modelReference;
    public string $fieldKey;
    public string $locale;
    public bool $allowExternalFiles = false;

    public function mount(?string $modelReference, string $fieldKey, string $locale, string $fieldName, array $assets = [], array $components = [])
    {
        $this->modelReference = $modelReference;
        $this->fieldKey = $fieldKey;
        $this->fieldName = $fieldName;
        $this->locale = $locale;

        $this->previewFiles = array_map(fn (AssetContract $asset) => PreviewFile::fromAsset($asset), $assets);
        $this->components = array_map(fn (\Thinktomorrow\Chief\Forms\Fields\Component $component) => $component, $components);
    }

    public function getListeners()
    {
        return [
            'upload:finished' => 'onUploadFinished',
            'upload:errored' => 'onUploadErrored',
            'assetUpdated' => 'onAssetUpdated',
            'assetsChosen-' . $this->id => 'onAssetsChosen',
        ];
    }

    public function booted()
    {
        $this->filePreview = new FilePreview($this);
        $this->fileSelect = new FileSelect(
            $this,
            true,
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
