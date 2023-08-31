<?php

namespace Thinktomorrow\Chief\Assets\App\ExternalFiles;

use Livewire\Component;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;

class FileFieldChooseExternalComponent extends Component
{
    use ShowsAsDialog;

    // TODO
    // v Set asset_type on save
    // refactor PreviewFile
    // previewfile:: account for external asset (in combo with preview asset)
    // all external asset values and preview values as separate data.
    // assert preview media relation works (with morph class for vimeoAsset)
    // previewFile::fromExternalAsset()
    // external preview blade
    // external file edit
    // Media gallery: filter by type: image - video - file
    // Rename Driver to Platform or Location ExternalLocation ?

    public $parentId;
    public $driverType;
    public $driverTypes = [];
    public $driverId;
    public $assetId = null; // Existing asset id

    private $cachedDriver = null;

    public function mount(string $parentId)
    {
        $this->parentId = $parentId;
        $this->driverTypes = array_keys(DriverFactory::$map);
        $this->driverType = reset($this->driverTypes);
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
        $this->assetId = $value['assetId'] ?? null;

        $this->isOpen = true;
    }

    public function getLabel()
    {
        return $this->getDriver()?->getCreateFormLabel();
    }

    public function getDescription()
    {
        return $this->getDriver()?->getCreateFormDescription();
    }

    private function getDriver(): ?Driver
    {
        if (! $this->driverType) {
            return null;
        }

        if ($this->cachedDriver) {
            return $this->cachedDriver;
        }

        return $this->cachedDriver = app(DriverFactory::class)->create($this->driverType);
    }

    public function save()
    {
        $this->validate(['driverId' => 'required'], ['driverId.required' => 'De id of link is verplicht in te vullen']);

        if (! $this->driverId) {
            return;
        }

        /** @var Driver $driver */
        $driver = $this->getDriver();

        if ($this->assetId) {
            $asset = $driver->updateAsset(Asset::find($this->assetId), $this->driverId);
            $this->dispatch('externalAssetUpdated-' . $this->parentId, [$asset->id]);
        } else {
            $asset = $driver->createAsset($this->driverId);
            $this->dispatch('assetsChosen-' . $this->parentId, [$asset->id]);
        }

        $this->reset('driverType', 'driverId', 'assetId');

        $this->close();
    }

    public function updatedDriverType()
    {
        $this->cachedDriver = null;
    }

    public function render()
    {
        return view('chief-assets::livewire.file-field-choose-external');
    }
}
