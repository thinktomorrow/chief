<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\App\StoreFiles;
use Thinktomorrow\Chief\Assets\Components\FilePreview;
use Thinktomorrow\Chief\Assets\Components\FileSelect;
use Thinktomorrow\Chief\Assets\Livewire\Traits\FileUploadDefaults;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Fields\Common\FormKey;

class FileUploadComponent extends Component implements HasPreviewFiles, HasSyncedFormInputs
{
    use WithFileUploads;
    use FileUploadDefaults;
    use ShowsAsDialog;

    public $parentId;

    protected $validationAttributes = [
        'files.0.fileRef' => 'bestand',
    ];

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
            'upload:finished' => 'onUploadFinished',
            'assetUpdated' => 'onAssetUpdated',
        ];
    }

    public function booted()
    {
        $this->filePreview = new FilePreview($this);
        $this->fileSelect = new FileSelect($this, false);

        $this->syncPreviewFiles();
    }

    public function render()
    {
        return view('chief-assets::file-upload', [
            //
        ]);
    }

    public function countFiles(): int
    {
        return collect($this->files)->reject(fn ($file) => !isset($file['fileRef']))->count();
    }

    public function submit($formData)
    {
        $formData = collect($formData)
            ->mapWithKeys(fn($value, $key) => [FormKey::replaceBracketsByDots($key) => $value])
            ->undot()
            ->get($this->fieldName);

        app(StoreFiles::class)->handle($formData);

        $this->reset(['previewFiles', 'files']);

        $this->emitUp('filesUploaded');

        $this->close();
    }
}
