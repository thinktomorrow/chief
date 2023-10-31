<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\ExternalFiles\DriverFactory;
use Thinktomorrow\Chief\Assets\App\StoreFiles;
use Thinktomorrow\Chief\Assets\Components\FilePreview;
use Thinktomorrow\Chief\Assets\Components\FileSelect;
use Thinktomorrow\Chief\Assets\Livewire\Traits\EmitsToNestables;
use Thinktomorrow\Chief\Assets\Livewire\Traits\FileUploadDefaults;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithChoosingAssets;
use Thinktomorrow\Chief\Assets\Livewire\Traits\RenamesErrorBagFileAttribute;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;

class FileUploadComponent extends Component implements HasPreviewFiles, HasSyncedFormInputs
{
    use WithFileUploads;
    use FileUploadDefaults;
    use InteractsWithChoosingAssets;
    use ShowsAsDialog;
    use RenamesErrorBagFileAttribute;
    use EmitsToNestables;

    public $parentId;

    public function mount(string $parentId, string $fieldName, array $assets = [], array $components = [])
    {
        $this->parentId = $parentId;
        $this->fieldName = $fieldName;

        $this->previewFiles = array_map(fn (Asset $asset) => PreviewFile::fromAsset($asset), $assets);
        $this->components = array_map(fn (\Thinktomorrow\Chief\Forms\Fields\Component $component) => $component, $components);
    }

    public function getListeners()
    {
        return [
            'open' => 'open',
            'open-' . $this->parentId => 'open',
            'upload:errored' => 'onUploadErrored',
            'upload:finished' => 'onUploadFinished',
            'assetUpdated-' . $this->getId() => 'onAssetUpdated',
            'assetsChosen-' . $this->getId() => 'onAssetsChosen',
        ];
    }

    public function booted()
    {
        $this->filePreview = new FilePreview($this);
        $this->fileSelect = new FileSelect($this, false, DriverFactory::any());

        $this->clearValidation();

        $this->syncPreviewFiles();
    }

    public function openFileEdit($fileId)
    {
        $this->emitDownTo('chief-wire::file-edit', 'open', ['previewfile' => $this->previewFiles[$this->findPreviewFileIndex($fileId)]]);
    }

    public function render()
    {
        $this->renameErrorBagFileAttribute();

        return view('chief-assets::file-upload', [
            //
        ]);
    }

    public function countUploadedOrSelectedFiles(): int
    {
        return count($this->previewFiles);
    }

    public function countFiles(): int
    {
        return collect($this->files)->reject(fn ($file) => ! isset($file['fileRef']))->count();
    }

    public function submit($formData)
    {
        $formData = collect($formData)
            ->mapWithKeys(fn ($value, $key) => [FormKey::replaceBracketsByDots($key) => $value])
            ->undot()
            ->get($this->fieldName);

        app(StoreFiles::class)->handle($formData);

        $this->reset(['previewFiles', 'files']);

        $this->dispatch('filesUploaded');

        $this->close();
    }
}
