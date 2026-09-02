<?php

namespace Thinktomorrow\Chief\Assets\UI\Livewire;

use Livewire\Attributes\Renderless;
use Livewire\Component;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Components\Gallery;
use Thinktomorrow\Chief\Assets\UI\Livewire\Traits\EmitsToNestables;
use Thinktomorrow\Chief\Assets\UI\Livewire\Traits\InteractsWithGallery;

class AssetGallery extends Component
{
    use EmitsToNestables;
    use InteractsWithGallery;

    public $sort = null;

    protected Gallery $table;

    public function getListeners()
    {
        return [
            'assetsDeleted' => 'onAssetsDeleted',
            'assetUpdated-'.$this->getId() => 'onAssetUpdated',
            'filesUploaded' => 'onFilesUploaded',
        ];
    }

    public function booted()
    {
        $this->table = new Gallery($this);
    }

    #[Renderless]
    public function openAssetEdit($assetId)
    {
        $previewFile = PreviewFile::fromAsset(Asset::find($assetId));

        $this->emitDownTo('chief-wire-assets::gallery-asset-editor', 'open', ['previewfile' => $previewFile]);
    }

    #[Renderless]
    public function openFileUpload()
    {
        $this->emitDownTo('chief-wire-assets::gallery-asset-uploader', 'open');
    }

    #[Renderless]
    public function deleteAsset($assetId)
    {
        $this->emitDownTo('chief-wire-assets::asset-delete-dialog', 'open', ['assetIds' => [$assetId]]);
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
        return view('chief-assets::livewire.asset-gallery', [
            //
        ]);
    }
}
