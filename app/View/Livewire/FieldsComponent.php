<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Managers\Register\Registry;

class FieldsComponent extends Component
{
    public $model;
    public string $componentKey;
    public bool $inlineEdit;

    public function mount($model, ?string $componentKey = null, bool $inlineEdit = false): void
    {
        $this->model = $model;
        $this->componentKey = $componentKey ?? 'default';
        $this->inlineEdit = $inlineEdit;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('chief::components.fieldscomponent', [
            'fields' => $this->componentKey !== "default"
                ? $this->model->fields()->model($this->model)->component($this->componentKey)
                : $this->model->fields()->model($this->model)->notTagged('component'),
            'manager' => app(Registry::class)->manager($this->model::managedModelKey()),
        ]);
    }

    public function reload(): void
    {
        $this->emit('fieldsComponentReloaded');
    }
}
