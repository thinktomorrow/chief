<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Livewire\Component;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Site\Urls\Form\LinkForm;
use Thinktomorrow\Chief\ManagedModels\States\WithPageState;

class Status extends Component
{
    public WithPageState $model;
    private $isAnyLinkOnline;
    private $isVisitable;
    public $class;

    public function mount(WithPageState $model): void
    {
        $this->model = $model;
        $this->reload();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('chief::manager.cards.status.statusComponent', [
            'isAnyLinkOnline' => $this->isAnyLinkOnline(),
            'isVisitable' => $this->isVisitable(),
            'manager' => app(Registry::class)->manager($this->model->managedModelKey()),
        ]);
    }

    public function reload(): void
    {
        $this->isAnyLinkOnline = $this->isAnyLinkOnline();
        $this->isVisitable = $this->isVisitable();

        $this->emit('statusReloaded');
    }

    private function isAnyLinkOnline(): bool
    {
        return ($this->isVisitable() && LinkForm::fromModel($this->model)->isAnyLinkOnline());
    }

    private function isVisitable(): bool
    {
        return $this->model instanceof Visitable;
    }
}
