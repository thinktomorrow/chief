<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;

class FieldsComponent extends Component
{
    public $model;
    public string $componentKey;
    public bool $inlineEdit;
    public $title;
    public $class;
    public $template;

    public function mount($model, ?string $componentKey = null): void
    {
        $this->model = $model;
        $this->componentKey = $componentKey ?? 'default';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('chief::manager.cards.fields.fieldsComponent', [
            'fields' => $this->componentKey !== "default"
                ? Fields::make($this->model->fields())->model($this->model)->component($this->componentKey)
                : Fields::make($this->model->fields())->model($this->model)->notTagged('component'),
            'manager' => app(Registry::class)->manager($this->model::managedModelKey()),
        ]);
    }

    public function reload(): void
    {
        $this->emit('fieldsComponentReloaded');
    }
}
