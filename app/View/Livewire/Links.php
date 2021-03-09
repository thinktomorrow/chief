<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\Site\Urls\ProvidesUrl\ProvidesUrl;

class Links extends Component
{
    public ProvidesUrl $model;
    private LinkForm $linkForm;

    public function mount(ProvidesUrl $model): void
    {
        $this->model = $model;
        $this->reload();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('chief::manager.cards.links.linksComponent', [
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
