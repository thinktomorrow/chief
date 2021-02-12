<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Managers\Register\Registry;

class FieldsComponent extends Component
{
    public $model;
    public string $componentKey;

    public function mount($model, string $componentKey)
    {
        $this->model = $model;
        $this->componentKey = $componentKey;
//        $this->reload();
    }

    public function render()
    {
        return view('chief::components.fieldscomponent', [
            'fields'  => $this->model->fields()->model($this->model),
            'manager' => app(Registry::class)->manager($this->model::managedModelKey()),
        ]);
    }

    public function reload()
    {


        $this->emit('fieldsComponentReloaded');
    }
}
