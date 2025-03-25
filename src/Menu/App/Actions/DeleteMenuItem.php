<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\App\Actions;

class DeleteMenuItem
{
    private string $menuItemId;

    public function __construct(string $menuItemId)
    {
        $this->menuItemId = $menuItemId;
    }

    public function getMenuItemId(): string
    {
        return $this->menuItemId;
    }
}
