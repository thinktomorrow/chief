<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\EditItem;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\TabItem;
use Thinktomorrow\Chief\Menu\App\Actions\DeleteMenu;
use Thinktomorrow\Chief\Menu\App\Actions\MenuApplication;
use Thinktomorrow\Chief\Menu\App\Actions\UpdateMenu;
use Thinktomorrow\Chief\Menu\Menu;

class EditMenu extends EditItem
{
    public string $type;

    public function mount(string $type)
    {
        $this->type = $type;
    }

    public function render()
    {
        return view('chief-menu::livewire.edit-menu');
    }

    protected function getItemById(string $itemId): TabItem
    {
        return app(ComposeLivewireDto::class)->getMenu(
            $this->type,
            $itemId,
        );
    }

    protected function handleDeleteItem(): void
    {
        app(MenuApplication::class)->safeDelete(new DeleteMenu(
            $this->item->getId(),
        ));
    }

    protected function handleUpdateItem(): void
    {
        app(MenuApplication::class)->update(new UpdateMenu(
            $this->item->getId(),
            $this->form['locales'],
            $this->form['active_sites'],
            $this->form['title'],
        ));
    }

    public function getItem(): TabItem
    {
        return $this->item;
    }

    public function getItemModels(): Collection
    {
        return Menu::where('type', $this->type)->get();
    }
}
