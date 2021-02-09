<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;

class Links extends Component
{
    public ProvidesUrl $model;
    private LinkForm $linkForm;

    public function mount(ProvidesUrl $model)
    {
        $this->model = $model;
        $this->reload();
    }

    public function render()
    {
        return view('chief::components.links', [
            'linkForm' => $this->linkForm,
            'manager' => app(Registry::class)->manager($this->model->managedModelKey()),
        ]);
    }

    public function reload()
    {
        $this->linkForm = LinkForm::fromModel($this->model);

        $this->emit('linksReloaded');
    }
}
