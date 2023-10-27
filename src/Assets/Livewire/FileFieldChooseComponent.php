<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Components\Gallery;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithGallery;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;

class FileFieldChooseComponent extends Component
{
    use ShowsAsDialog;
    use InteractsWithGallery;

    public $assetIds = [];
    public array $existingAssetIds = [];
    public $selectedPreviewFiles = [];
    public $parentId;
    protected Gallery $gallery;
    public bool $allowMultiple = false;

    public function mount(string $parentId)
    {
        $this->parentId = $parentId;
        $this->rows = collect();
    }

    public function getListeners()
    {
        return [
            'open' => 'open',
            'open-' . $this->parentId => 'open',
        ];
    }

    public function open($arguments)
    {
        $this->existingAssetIds = $arguments['existingAssetIds'];

        $this->isOpen = true;
    }

    public function booted()
    {
        $this->gallery = new Gallery($this);
        $this->syncPreviewFiles();
    }

    public function selectAsset($assetId)
    {
        if (! $this->allowMultiple) {
            $this->assetIds = [$assetId];
            $this->selectedPreviewFiles = [$assetId => PreviewFile::fromAsset(Asset::find($assetId))];

            return;
        }

        if (! in_array($assetId, $this->assetIds) && ! in_array($assetId, $this->existingAssetIds)) {
            $this->assetIds[] = $assetId;
            $this->selectedPreviewFiles[$assetId] = PreviewFile::fromAsset(Asset::find($assetId));
        }
    }

    private function syncPreviewFiles()
    {
        // Livewire converts the public properties of PreviewFile object to an array. So we need to convert this back to an object
        $this->selectedPreviewFiles = array_map(fn (array|PreviewFile $file) => $file instanceof PreviewFile ? $file : PreviewFile::fromArray($file), $this->selectedPreviewFiles);
    }

    public function save()
    {
        $this->dispatch('assetsChosen-' . $this->parentId, $this->assetIds);

        $this->close();
    }

    public function close()
    {
        $this->reset('assetIds', 'selectedPreviewFiles');

        $this->isOpen = false;
    }

    public function render()
    {
        return view('chief-assets::file-field-choose');
    }
}
