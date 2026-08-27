<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Resources;

use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Table\Table;

interface MenuItemResource extends Resource
{
    public function configureTable(Table $table, Menu $menu): Table;
}
