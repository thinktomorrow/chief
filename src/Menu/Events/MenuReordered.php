<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Events;

class MenuReordered
{
    public function __construct(public readonly string $menuId) {}
}
