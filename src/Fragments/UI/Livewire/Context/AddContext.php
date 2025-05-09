<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\Context;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\CreateContext;
use Thinktomorrow\Chief\Fragments\App\ContextActions\DuplicateContext;
use Thinktomorrow\Chief\Fragments\App\ContextActions\UpdateContext;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\AddItem;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\TabItem;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class AddContext extends AddItem
{
    public string $modelReference;

    // Memoized collection
    private ?Collection $otherContexts = null;

    public function mount(string $modelReference, array $locales)
    {
        $this->modelReference = $modelReference;

        $this->mountAddItem($locales);
    }

    public function getItem(): TabItem
    {
        return app(ComposeLivewireDto::class)->composeEmptyContext(ModelReference::fromString($this->modelReference));
    }

    /** @return Collection<ContextDto> */
    public function getItems(): Collection
    {
        if ($this->otherContexts) {
            return $this->otherContexts;
        }

        return $this->otherContexts = app(ComposeLivewireDto::class)
            ->getContextsByOwner(ModelReference::fromString($this->modelReference));
    }

    protected function createOnSave(): string
    {
        if (isset($this->form['duplicate_from']) && $this->form['duplicate_from'] === '1') {
            $sourceContextId = $this->form['duplicate_from_item_id'] ?? $this->getItems()->first()?->getId();

            $contextId = app(ContextApplication::class)->duplicate(new DuplicateContext(
                $sourceContextId,
                ModelReference::fromString($this->modelReference)->instance(),
            ));

            app(ContextApplication::class)->update(new UpdateContext(
                $contextId,
                $this->form['locales'] ?? [],
                [],
                $this->form['title'] ?? null
            ));

            return $contextId;
        }

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
