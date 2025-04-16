<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\Context;

use Livewire\Component;
use Thinktomorrow\Chief\Fragments\UI\Livewire\_partials\WithFragments;

class Context extends Component
{
    use WithFragments;

    public ContextDto $context;

    public function mount(ContextDto $context, ?string $scopedLocale = null)
    {
        $this->context = $context;

        if (! $scopedLocale) {
            $this->scopedLocale = count($context->getActiveSites()) > 0
                ? $context->getActiveSites()[0]
                : $context->getActiveSites()[0];
        }

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
