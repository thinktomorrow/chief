<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Livewire\Component;
use Thinktomorrow\AssetLibrary\Application\DeleteAsset;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\Exceptions\FileNotAccessibleException;

class AssetDeleteComponent extends Component
{
    public $isOpen = false;
    public $parentId;

    public $assetIds = [];

    public function mount(string $parentId)
    {
        $this->parentId = $parentId;
    }

    public function getListeners()
    {
        return [
            'open' => 'open',
            'open-' . $this->parentId => 'open',
        ];
    }

    public function open($value)
    {
        $this->isOpen = true;
        $this->assetIds = $value['assetIds'];
    }

    public function close()
    {
        $this->reset(['assetIds']);
        $this->isOpen = false;
    }

    public function submit()
    {
        if(count($this->assetIds) > 0) {
            foreach($this->assetIds as $assetId) {

                // TODO: this exception is thrown by current version of assetlib and is in new version not necessary
                try {
                    app(DeleteAsset::class)->delete($assetId);
                } catch(FileNotAccessibleException $e) {
                    Asset::find($assetId)->delete();
                }
            }

            $this->emitUp('assetsDeleted', $this->assetIds);
        }

        $this->close();
    }

    public function render()
    {
        return view('chief-form::fields.file.asset-delete', [
            //
        ]);
    }
}
