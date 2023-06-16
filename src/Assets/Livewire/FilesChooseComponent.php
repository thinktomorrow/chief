<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Components\Gallery;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithGallery;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;

class FilesChooseComponent extends Component
{
    use ShowsAsDialog;
    use InteractsWithGallery;

    public $assetIds = [];
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
    }

    public function selectAsset($assetId)
    {
        $this->assetIds[] = $assetId;
    }

    public function save()
    {
        $this->emit('assetsChosen-'.$this->parentId, $this->assetIds);
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
