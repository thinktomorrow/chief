<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class Links extends Component
{
    public Visitable $model;
    private LinkForm $linkForm;
    public $class;

    public function mount(Visitable $model): void
    {
        $this->model = $model;
        $this->reload();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('chief::manager.windows.links.component', [
            'linkForm' => $this->linkForm,
            'manager' => app(Registry::class)->manager($this->model->managedModelKey()),
        ]);
    }

    public function reload(): void
    {
        $this->linkForm = LinkForm::fromModel($this->model);

        $this->emit('linksReloaded');
    }
}
