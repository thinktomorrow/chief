<?php

namespace Thinktomorrow\Chief\Menu\UI\Livewire;

use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\AddItem;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\TabItem;
use Thinktomorrow\Chief\Menu\App\Actions\CreateMenu;
use Thinktomorrow\Chief\Menu\App\Actions\MenuApplication;

class AddMenu extends AddItem
{
    public string $type;

    public function mount(string $type)
    {
        $this->type = $type;
    }

    public function render()
    {
        return view('chief-menu::livewire.add-menu');
    }

    public function getItem(): TabItem
    {
        return MenuDto::empty($this->type);
    }

    protected function createOnSave(): string
    {
        return app(MenuApplication::class)->create(new CreateMenu(
            $this->type,
            $this->form['locales'],
            $this->form['active_sites'],
            $this->form['title'],
        ));
    }
}
