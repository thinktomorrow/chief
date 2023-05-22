<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Components\Gallery;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithGallery;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\Traits\WithListAndGridToggle;

class GalleryComponent extends Component
{
    use InteractsWithGallery;

    public $sort = null;

    public Collection $rows;
    protected Gallery $table;

    protected $listeners = [
        'assetsDeleted' => 'onAssetsDeleted',
        'assetUpdated' => 'onAssetUpdated',
    ];

    public function mount()
    {
        $this->rows = collect();
    }

    public function booted()
    {
        $this->table = new Gallery($this);
    }

    public function openAssetEdit($assetId)
    {
        $previewFile = PreviewFile::fromAsset(Asset::find($assetId));

        $this->emitDownTo('chief-wire::file-edit', 'open', ['previewfile' => $previewFile]);
    }

    public function deleteAsset($assetId)
    {
        $this->emitDownTo('chief-wire::asset-delete', 'open', ['assetIds' => [$assetId]]);
    }

    public function onAssetUpdated($assetId): void
    {
        $this->callMethod('$refresh');
    }

    public function onAssetsDeleted(array $assetIds): void
    {
        // TODO: show toast of deletion

        $this->callMethod('$refresh');
    }

    public function render()
    {
        return view('chief-assets::gallery-component', [
            //
        ]);
    }

    private function emitDownTo($name, $event, array $params = [])
    {
        $this->emitTo($name, $event . '-' . $this->id, $params);
    }
}
