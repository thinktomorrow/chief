<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\Context;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\DeleteContext;
use Thinktomorrow\Chief\Fragments\App\ContextActions\UpdateContext;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\EditItem;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\TabItem;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class EditContext extends EditItem
{
    public string $modelReference;

    public function mount(string $modelReference, array $locales)
    {
        $this->modelReference = $modelReference;

        $this->mountEditItem($locales);
    }

    protected function getItemById(string $itemId): TabItem
    {
        return app(ComposeLivewireDto::class)->getContext(ModelReference::fromString($this->modelReference), $itemId);
    }

    protected function handleDeleteItem(): void
    {
        app(ContextApplication::class)->safeDelete(new DeleteContext(
            $this->item->getId(),
        ));
    }

    protected function handleUpdateItem(): void
    {
        app(ContextApplication::class)->update(new UpdateContext(
            $this->item->getId(),
            $this->form['locales'] ?? [],
            $this->form['active_sites'] ?? [],
            $this->form['title'] ?? null
        ));
    }

    public function render()
    {
        return view('chief-fragments::livewire.edit-context');
    }

    public function getItem(): TabItem
    {
        return $this->item;
    }

    public function getItemModels(): Collection
    {
        return app(ContextRepository::class)->getByOwner($this->item->ownerReference);
    }
}
