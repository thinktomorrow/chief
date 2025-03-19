<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class Contexts extends Component
{
    public string $resourceKey;

    public string $modelReference;

    public function mount(string $resourceKey, ReferableModel&ContextOwner $model)
    {
        $this->resourceKey = $resourceKey;
        $this->modelReference = $model->modelReference()->get();
    }

    /** @return Collection<ContextDto> */
    public function getContexts(): Collection
    {
        return app(ComposeLivewireDto::class)
            ->getContextsByOwner(ModelReference::fromString($this->modelReference));
    }

    public function render()
    {
        return view('chief-fragments::livewire.contexts');
    }
}
