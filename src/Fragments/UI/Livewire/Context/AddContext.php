<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\Context;

use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\CreateContext;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\AddItem;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\TabItem;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class AddContext extends AddItem
{
    public string $modelReference;

    public function mount(string $modelReference, array $locales)
    {
        $this->modelReference = $modelReference;

        $this->mountAddItem($locales);
    }

    public function getItem(): TabItem
    {
        return app(ComposeLivewireDto::class)->composeEmptyContext(ModelReference::fromString($this->modelReference));
    }

    protected function createOnSave(): string
    {
        return app(ContextApplication::class)->create(new CreateContext(
            ModelReference::fromString($this->modelReference),
            $this->form['locales'] ?? [],
            [], // No active sites on adding new context
            $this->form['title'] ?? null
        ));
    }

    public function render()
    {
        return view('chief-fragments::livewire.add-context');
    }
}
