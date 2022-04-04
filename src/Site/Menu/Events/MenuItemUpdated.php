<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu\Events;

class MenuItemUpdated
{
    public function __construct(public readonly string $menuItemId)
    {

    }
}
