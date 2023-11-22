<?php

namespace Thinktomorrow\Chief\Plugins\HotSpots;

use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Thinktomorrow\Chief\Assets\App\FileApplication;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithForm;
use Thinktomorrow\Chief\Assets\Livewire\Traits\InteractsWithGroupedForms;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Fields\Field;

class HotSpotComponent extends Component
{
    use ShowsAsDialog;
    use WithFileUploads;
    use InteractsWithForm;
    use InteractsWithGroupedForms;

    public $parentId;
    public $previousSiblingId;
    public ?PreviewFile $previewFile = null;

    public $hotSpots = [];
    public $activeHotSpotId = null;

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
        // The hotspot component is opened from the file edit modal so this is our reference.
        $this->previousSiblingId = $value['previous_sibling_id'];

        $this->setFile(is_array($value['previewfile']) ? PreviewFile::fromArray($value['previewfile']) : $value['previewfile']);

        $this->isOpen = true;

        if ($this->previewFile->getData('hotspots') && is_array($this->previewFile->getData('hotspots'))) {
            $this->hotSpots = $this->previewFile->getData('hotspots');

            $this->activateFirstHotSpot();
        }

        $this->extractGroupedFormComponents();
    }

    private function setFile(PreviewFile $previewFile)
    {
        $this->previewFile = $previewFile;

        $this->addAssetComponents('hotSpotFields');

        $this->extractGroupedFormComponents();
    }

    private function activateFirstHotSpot()
    {
        if (count($this->hotSpots) > 0 && $firstHotSpot = reset($this->hotSpots)) {
            $this->activateHotSpot($firstHotSpot['id']);
        }
    }

    public function activateHotSpot(string $id)
    {
        $this->activeHotSpotId = $id;
    }

    public function addHotSpot(float $x, float $y, $relativeTop, $relativeLeft)
    {
        $this->hotSpots[$id = strtolower(Str::random())] = [
            'id' => $id,
            'product_id' => null,
            'top' => $relativeTop,
            'left' => $relativeLeft,
            'x' => (int)$x,
            'y' => (int)$y,
        ];

        $this->activeHotSpotId = $id;

        $this->extractGroupedFormComponents();
    }

    public function removeHotSpot(string $id)
    {
        unset($this->hotSpots[$id]);

        if ($this->activeHotSpotId == $id) {
            $this->activateFirstHotSpot();
        }

        $this->resetErrorBag();
    }

    public function submit()
    {
        // Only validate when hotspot entries are present - otherwise Livewire complains about a missing $rules property.
        if (count($this->hotSpots) > 0) {
            $this->validateForm();
        }

        // Merge hotspot coordinates with form values
        $hotspots = collect($this->hotSpots)->mapWithKeys(function ($hotspot) {
            return [$hotspot['id'] => array_merge($hotspot, $this->form['hotspots'][$hotspot['id']])];
        })->all();

        app(FileApplication::class)->updateAssetData($this->previewFile->mediaId, ['hotspots' => $hotspots]);

        // Sync previewFile
        $this->previewFile->fieldValues = array_merge($this->previewFile->fieldValues, $this->form);
        $this->previewFile->data = array_merge($this->previewFile->data, ['hotspots' => $hotspots]);

        $this->dispatch('assetUpdated-' . $this->previousSiblingId, $this->previewFile);

        $this->close();
    }

    public function close()
    {
        $this->reset(['previewFile', 'form', 'components', 'hotSpots', 'activeHotSpotId']);
        $this->hotSpots = [];

        $this->isOpen = false;
    }

    public function render()
    {
        return view('chief-hotspots::hotspot-component', [
            //
        ]);
    }

    private function composeGroupIndex($index)
    {
        return 'hotspots.' . $index;
    }

    private function componentIndices(): array
    {
        return collect($this->hotSpots)
            ->map(fn ($hotSpot) => $hotSpot['id'])
            ->all();
    }

    private function getFieldsForValidation(): array
    {
        return collect($this->getGroupedComponents())
            ->flatten()
            ->reject(fn ($component) => ! $component instanceof Field)
            ->all();
    }
}
