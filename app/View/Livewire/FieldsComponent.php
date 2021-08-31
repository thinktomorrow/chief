<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Managers\Register\Registry;

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
        $fields = Fields::make($this->model->fields())
            ->model($this->model)
            ->filterByWindowId($this->componentKey);

        return view('chief::manager.windows.fields.fieldsComponent', [
            'fields' => $fields,
            'manager' => app(Registry::class)->manager($this->model::managedModelKey()),
        ]);
    }

    public function reload(): void
    {
        $this->emit($this->componentKey. 'Reloaded');
    }
}
