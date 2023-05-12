<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Components;

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

    private function getView(): string
    {
        if($this->livewireComponent->showAsList) {
            return 'chief-form::fields.file.gallery-list';
        }

        return 'chief-form::fields.file.gallery';
    }

    public function render(): View
    {
        return view($this->getView(), array_merge($this->data(), [

        ]));
    }
}
