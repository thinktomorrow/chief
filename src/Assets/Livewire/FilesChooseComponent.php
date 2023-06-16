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
        // TODO(ben): deselect bro
        $this->assetIds[] = $assetId;
        // dd($this->gallery->getRows());
        $this->selectedPreviewFiles[] = PreviewFile::fromAsset(Asset::find($assetId));
    }

    private function syncPreviewFiles()
    {
        // Livewire converts the public properties of PreviewFile object to an array. So we need to convert this back to an object
        $this->selectedPreviewFiles = array_map(fn (array|PreviewFile $file) => $file instanceof PreviewFile ? $file : PreviewFile::fromArray($file), $this->selectedPreviewFiles);
    }

    public function save()
    {
        $this->emit('assetsChosen-'.$this->parentId, $this->assetIds);

        $this->reset('assetIds');

        $this->close();

    }

    public function render()
    {
        return view('chief-assets::files-choose', [
            //
        ]);
    }

    public function paginationView()
    {
        return 'chief::pagination.livewire-default';
    }
}
