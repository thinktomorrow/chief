<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Components\Gallery;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithGallery;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;

class FilesChooseComponent extends Component
{
    use ShowsAsDialog;
    use InteractsWithGallery;

    public $assetIds = [];
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

    public function booted()
    {
        $this->gallery = new Gallery($this);
        $this->syncPreviewFiles();
    }

    public function selectAsset($assetId)
    {
        if(! $this->allowMultiple) {
            $this->assetIds = [$assetId];
            $this->selectedPreviewFiles = [$assetId => PreviewFile::fromAsset(Asset::find($assetId))];

            return;
        }

        // TODO: already selected assetsIds should be disabled, not selectable
        if(in_array($assetId, $this->assetIds)) {
            unset($this->assetIds[array_search($assetId, $this->assetIds)]);
            unset($this->selectedPreviewFiles[$assetId]);
        } else {
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
        $this->emit('assetsChosen-'.$this->parentId, $this->assetIds);

        $this->reset('assetIds', 'selectedPreviewFiles');

        $this->close();
    }

    public function render()
    {
        return view('chief-assets::files-choose');
    }
}
