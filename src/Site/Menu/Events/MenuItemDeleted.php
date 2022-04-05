<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Events;

class MenuItemDeleted
{
    public function __construct(public readonly string $menuItemId)
    {
    }
}
