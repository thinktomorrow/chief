<?php

namespace Thinktomorrow\Chief\Assets\Livewire;

use Livewire\Component;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Components\Gallery;
use Thinktomorrow\Chief\Assets\Livewire\Traits\EmitsToNestables;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithGallery;

class GalleryComponent extends Component
{
    use InteractsWithGallery;
    use EmitsToNestables;

    public $sort = null;

    protected Gallery $table;

    public function getListeners()
    {
        return [
            'assetsDeleted' => 'onAssetsDeleted',
            'assetUpdated-' . $this->getId() => 'onAssetUpdated',
            'filesUploaded' => 'onFilesUploaded',
        ];
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

    public function openFileUpload()
    {
        $this->emitDownTo('chief-wire::file-upload', 'open');
    }

    public function deleteAsset($assetId)
    {
        $this->emitDownTo('chief-wire::asset-delete', 'open', ['assetIds' => [$assetId]]);
    }

    public function onAssetUpdated($assetId): void
    {
        // $this->callMethod('$refresh');
    }

    public function onFilesUploaded(): void
    {
        // $this->callMethod('$refresh');
    }

    public function onAssetsDeleted(array $assetIds): void
    {
        // TODO: show toast of deletion
        // $this->callMethod('$refresh');
    }

    public function render()
    {
        return view('chief-assets::gallery-component', [
            //
        ]);
    }
}
