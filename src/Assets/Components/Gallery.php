<?php

namespace Thinktomorrow\Chief\Assets\Components;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Gallery extends Component implements Htmlable
{
    private \Livewire\Component $livewireComponent;

    public function __construct(\Livewire\Component $livewireComponent)
    {
        $this->livewireComponent = $livewireComponent;
    }

    public function toHtml()
    {
        return $this->render()->render();
    }

    public function getActions()
    {
        return 'ddd';
    }

    public function getRows(): Collection|Paginator
    {
        return $this->livewireComponent->getTableRows();
    }

    public function getFilters(): Array
    {
        return $this->livewireComponent->getFilters();
    }

    private function getView(): string
    {
        return 'chief-assets::components.gallery';
    }

    public function render(): View
    {
        return view($this->getView(), array_merge($this->data(), [

        ]));
    }
}
