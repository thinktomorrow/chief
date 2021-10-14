<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Managers\Register\Registry;

class FieldsComponent extends Component
{
    public $model;
    public string $tag;
    public bool $inlineEdit;
    public $title;
    public $class;
    public $template;

    public function mount($model, ?string $tag = null): void
    {
        $this->model = $model;
        $this->tag = $tag ?? 'default';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $fields = Fields::make($this->model->fields())
            ->tagged($this->tag)
            ->model($this->model);

        return view('chief::manager.windows.fields.fieldsComponent', [
            'fields' => $fields,
            'manager' => app(Registry::class)->manager($this->model::managedModelKey()),
        ]);
    }

    public function reload(): void
    {
        $this->emit($this->tag. 'Reloaded');
    }
}
