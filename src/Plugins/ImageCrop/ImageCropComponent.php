<?php

namespace Thinktomorrow\Chief\Plugins\ImageCrop;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\PreviewFile;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;

class ImageCropComponent extends Component
{
    use ShowsAsDialog;

    public $parentId;

    public ?PreviewFile $previewFile = null;
    public $form = [];

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
        $this->setFile(is_array($value['previewfile']) ? PreviewFile::fromArray($value['previewfile']) : $value['previewfile']);
        $this->isOpen = true;
    }

    private function setFile(PreviewFile $previewFile)
    {
        $this->previewFile = $previewFile;

        // SET FORM EXISTING VALUES: X, Y, ...?
        // $this->form['x'] = '...';
    }

    public function submit()
    {
        // SAVE this->form values...
        // AND PERSIST CROP...
        // DO KEEP ORIGINAL!!

        // Update form values
        $this->syncForm();

        $this->dispatch('assetCropped', $this->previewFile);

        $this->close();
    }

    private function syncForm()
    {
        $this->previewFile->fieldValues = $this->form;

        $this->form['basename'] = $this->previewFile->getBaseName();
    }

    public function close()
    {
        $this->reset(['previewFile', 'form']);
        $this->isOpen = false;
    }

    public function render()
    {
        return view('chief-image-crop::crop-component', [
            //
        ]);
    }
}
