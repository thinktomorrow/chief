<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Livewire\Component;

class FilesChooseComponent extends Component
{
    public $isOpen = false;
    public $filters = [];
    public $parentId;

    public $listeners = [
        'openInParentScope' => 'openInParentScope',
        'open' => 'open',
    ];

    public function mount(string $parentId)
    {
        $this->parentId = $parentId;
    }

    public function openInParentScope($value)
    {
        if(! isset($value['parent_id']) || $this->parentId !== $value['parent_id']) {
            return;
        }

        $this->open();
    }

    public function open()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
    }

    public function submitFilter()
    {
        // Filter results
        // Sort
        // Paginate

    }

    public function render()
    {
        return view('chief-form::fields.file.files-choose', [
            //
        ]);
    }
}
