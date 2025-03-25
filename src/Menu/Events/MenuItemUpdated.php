<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Events;

class MenuItemUpdated
{
    public function __construct(public readonly string $menuItemId) {}
}
