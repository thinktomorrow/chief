<?php

namespace Thinktomorrow\Chief\Forms\UI\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\Layouts\Layout;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FormComponent extends Component
{
    public ModelReference $modelReference;

    public Form $form;

    public function mount(ModelReference $modelReference, \Thinktomorrow\Chief\Forms\Layouts\Form $form)
    {
        $this->modelReference = $modelReference;
        $this->form = $form;
    }

    public function getListeners()
    {
        return [
            'form-updated-'.$this->getId() => 'onFormUpdated',
        ];
    }

    /**
     * Compose the form again so we get all the closures
     * and such of all fields and layouts
     */
    public function getComponents(): array
    {
        $model = $this->modelReference->instance();
        $resource = app(Registry::class)->findResourceByModel($model::class);

        $layout = Layout::make($resource->fields($model));

        return $layout->findForm($this->form->getId())
            ->model($model)
            ->getComponents();
    }

    public function editForm(): void
    {
        $this->dispatch('open-'.$this->getId())->to('chief-wire::edit-form');
    }

    public function onFormUpdated(): void
    {
        // Refresh is done automatically by livewire when this method is called
    }

    public function render()
    {
        if ($this->form->getFormDisplay() == 'inline') {
            return view('chief-form::livewire.form-inline');
        }

        if ($this->form->getFormDisplay() == 'compact') {
            return view('chief-form::livewire.form-compact');
        }

        return view('chief-form::livewire.form');
    }
}
