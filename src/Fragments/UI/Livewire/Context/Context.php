<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\Context;

use Livewire\Component;
use Thinktomorrow\Chief\Fragments\UI\Livewire\_partials\WithFragments;

class Context extends Component
{
    use WithFragments;

    public ContextDto $context;

    public function mount(ContextDto $context)
    {
        $this->context = $context;
        $this->refreshFragments();
    }

    public function getListeners()
    {
        return array_merge(
            $this->getListenersWithFragments(),
            []
        );
    }

    public function render()
    {
        return view('chief-fragments::livewire.context');
    }
}
