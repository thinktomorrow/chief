<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Events;

class MenuDeleted
{
    public function __construct(public readonly string $menuId) {}
}
