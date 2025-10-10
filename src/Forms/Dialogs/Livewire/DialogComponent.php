<?php

namespace Thinktomorrow\Chief\Forms\Dialogs\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Assets\Livewire\Traits\ShowsAsDialog;
use Thinktomorrow\Chief\Forms\Dialogs\Concerns\HasForm;
use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Sites\UI\Livewire\WithLocaleToggle;

class DialogComponent extends Component
{
    use HasForm;
    use ShowsAsDialog;
    use WithLocaleToggle;

    public $parentId;

    // Data is passed from the table component to the dialog component, depending on the action type
    public array $data = [];

    public ?DialogReference $dialogReference = null;

    private ?Dialog $dialog = null;

    public function mount(string $parentId)
    {
        $this->parentId = $parentId;
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
        $this->dialogReference = $value['dialogReference']['class']::fromLivewire($value['dialogReference']);

        // how to convert to model(s) from data;
        $this->data = $value['data'];

        $this->initializeLocales($this->data, $this->getFields());

        $this->isOpen = true;
    }

    public function close()
    {
        $this->resetExcept(['parentId']);

        $this->isOpen = false;
    }

    private function getDialog(): ?Dialog
    {
        if (! $this->dialogReference) {
            return null;
        }

        if (! $this->dialog) {
            $this->dialog = $this->dialogReference->getDialog([$this->data]);
        }

        return $this->dialog;
    }

    public function getTitle()
    {
        return $this->getDialog()->getTitle();
    }

    public function getFields(): iterable
    {
        return $this->getDialog()->getComponents();
    }

    public function getSubTitle()
    {
        return str_replace(':count', $this->getItemsCount(), $this->getDialog()->getSubTitle());
    }

    public function getContent()
    {
        return str_replace(':count', $this->getItemsCount(), $this->getDialog()->getContent());
    }

    public function getButton()
    {
        return $this->getDialog()->getButton();
    }

    public function getButtonVariant()
    {
        return $this->getDialog()->getButtonVariant();
    }

    private function getItemsCount(): int
    {
        return count($this->data['items'] ?? []);
    }

    public function save()
    {
        $this->validateForm();

        $this->dispatch('dialogSaved-'.$this->parentId, [
            'dialogReference' => $this->dialogReference->toLivewire(),
            'form' => $this->form,
            'data' => $this->data,
        ]);

        // 1. data state
        // 2. VALIDATE!!
        // 3. execute effect
        // 4. notify response to table component

        // $this->validate(['driverId' => 'required'], ['driverId.required' => 'De id of link is verplicht in te vullen']);

        // execute effect of dialog...

        // $this->getModal()->getEffect()($model, $data);

        $this->resetExcept(['parentId']);

        $this->close();
    }

    public function render()
    {
        $type = 'modal';

        if ($this->getDialog()) {
            $type = $this->getDialog()->getType()->value;
        }

        return view('chief-form::livewire.'.$type);
    }
}
