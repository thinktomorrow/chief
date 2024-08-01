<?php

namespace Thinktomorrow\Chief\Forms\Modals\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Modals\Concerns\InteractsWithForm;
use Thinktomorrow\Chief\Forms\Modals\Modal;

class ModalComponent extends Component
{
    use ShowsAsDialog;
    use InteractsWithForm;

    public $parentId;

    // Data is passed from the table component to the modal component, depending on the action type
    public array $data = [];

    public ?ModalReference $modalReference = null;
    private ?Modal $modal = null;

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
        $this->modalReference = $value['modalReference']['class']::fromLivewire($value['modalReference']);

        // how to convert to model(s) from data;
        $this->data = $value['data'];

        $this->isOpen = true;
    }

    private function getModal(): Modal
    {
        if (! $this->modal) {
            $this->modal = $this->modalReference->getModal();
        }

        return $this->modal;
    }

    public function getTitle()
    {
        return $this->getModal()->getTitle();
    }

    public function getFields(): iterable
    {
        return $this->getModal()->getComponents();
    }

    public function getSubTitle()
    {
        return str_replace(':count', $this->getItemsCount(), $this->getModal()->getSubTitle());
    }

    public function getContent()
    {
        return str_replace(':count', $this->getItemsCount(), $this->getModal()->getContent());
    }

    public function getButton()
    {
        return $this->getModal()->getButton();
    }

    private function getItemsCount(): int
    {
        return count($this->data['items'] ?? []);
    }

    public function save()
    {
        $this->validateForm();

        $this->dispatch('modalSaved-' . $this->parentId, [
            'modalReference' => $this->modalReference->toLivewire(),
            'form' => $this->form,
            'data' => $this->data,
        ]);

        // 1. data state
        // 2. VALIDATE!!
        // 3. execute effect
        // 4. notify response to table component

        //$this->validate(['driverId' => 'required'], ['driverId.required' => 'De id of link is verplicht in te vullen']);

        // execute effect of modal...

        //$this->getModal()->getEffect()($model, $data);

        $this->resetExcept(['parentId']);

        $this->close();
    }

    public function render()
    {
        return view('chief-form::livewire.modal');
    }
}
