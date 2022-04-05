<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Menu;

enum MenuItemStatus: string
{
    case online = 'online';
    case offline = 'offline';
}
