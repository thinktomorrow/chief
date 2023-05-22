<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Forms\Fields\File\Components\Gallery;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Fields\File\Livewire\Traits\InteractsWithGallery;

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
        $this->emitUp('assetsChosen', $this->assetIds);
        $this->close();

    }

    public function render()
    {
        return view('chief-form::fields.file.files-choose', [
            //
        ]);
    }
}
