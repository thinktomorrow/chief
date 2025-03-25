<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

class DeleteMenu
{
    private string $menuId;

    public function __construct(string $menuId)
    {
        $this->menuId = $menuId;
    }

    public function getMenuId(): string
    {
        return $this->menuId;
    }
}
