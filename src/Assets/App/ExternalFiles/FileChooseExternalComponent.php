<?php

namespace Thinktomorrow\Chief\Assets\App\ExternalFiles;

use Livewire\Component;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;

class FileChooseExternalComponent extends Component
{
    use ShowsAsDialog;

    public $parentId;

    public $driverType;

    public $driverTypes = [];

    public $driverId;

    public $assetId = null; // Existing asset id

    private array $cachedDrivers = [];

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
            'open-'.$this->parentId => 'open',
        ];
    }

    public function open($value)
    {
        $this->assetId = $value['assetId'] ?? null;
        $this->driverType = (isset($value['driverType']) && in_array($value['driverType'], $this->driverTypes))
            ? $value['driverType']
            : reset($this->driverTypes);

        $this->isOpen = true;
    }

    public function getLabel()
    {
        return $this->getDriver()?->getCreateFormLabel();
    }

    private function getDriver(): ?Driver
    {
        if (! $this->driverType) {
            return null;
        }

        if (isset($this->cachedDrivers[$this->driverType])) {
            return $this->cachedDrivers[$this->driverType];
        }

        return $this->cachedDrivers[$this->driverType] = app(DriverFactory::class)->create($this->driverType);
    }

    public function getDescription()
    {
        return $this->getDriver()?->getCreateFormDescription();
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
            $this->dispatch('externalAssetUpdated-'.$this->parentId, [$asset->id]);
        } else {
            $asset = $driver->createAsset($this->driverId);
            $this->dispatch('assetsChosen-'.$this->parentId, [$asset->id]);
        }

        $this->reset('driverType', 'driverId', 'assetId');

        $this->close();
    }

    //    public function updatedDriverType()
    //    {
    //        $this->cachedDriver = null;
    //    }

    public function render()
    {
        return view('chief-assets::livewire.file-choose-external');
    }
}
