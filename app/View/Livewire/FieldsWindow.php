<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\Managers\Register\Registry;

class FieldsWindow extends Component
{
    public $model;
    public $tag;
    public $title;
    public $class;
    public $template;

    public function mount($model, $tag): void
    {
        $this->model = $model;
        $this->tag = $tag;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $fields = Fields::make($this->model->fields());

        $fields = ($this->tag === "untagged") ? $fields->untagged() : $fields->tagged($this->tag);
        $fields = $fields->model($this->model);

        return view('chief::components.field.window-livewire', [
            'key' => $this->tag,
            'fields' => $fields,
            'manager' => app(Registry::class)->manager($this->model::managedModelKey()),
        ]);
    }

    public function reload(): void
    {
        $this->emit($this->tag. 'Reloaded');
    }
}
