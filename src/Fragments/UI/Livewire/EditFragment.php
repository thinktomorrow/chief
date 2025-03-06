<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;

class EditFragment extends Component
{
    use HasForm;
    use InteractsWithFields;
    use ShowsAsDialog;

    public $parentId; // parent livewire component id

    public FragmentDto $fragment;

    public function mount(string $parentId, FragmentDto $fragment)
    {
        $this->parentId = $parentId;
        $this->fragment = $fragment;
    }

    public function getListeners()
    {
        return [
            'open' => 'open',
            'open-'.$this->parentId => 'open',
        ];
    }

    public function open()
    {
        $this->isOpen = true;
    }

    public function close()
    {
        $this->resetExcept(['parentId']);

        $this->isOpen = false;
    }

    public function getFields(): iterable
    {
        $this->injectFormValues($this->fragment->fields);

        return $this->fragment->fields;
    }

    public function save()
    {
        $this->validateForm();

        $this->dispatch('dialogSaved-'.$this->parentId, [
            'form' => $this->form,
            'data' => $this->data,
        ]);

        $this->close();
    }

    public function render()
    {
        return view('chief-fragments::livewire.edit-fragment');
    }
}
