<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Table\Concerns\HasUrl;

class TableCellIcon extends TableCell
{
    use HasUrl;

    protected string $view = 'chief-table::cells.icon';
}
