<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Table\Concerns\HasUrl;
use Thinktomorrow\Chief\Table\Concerns\HasColor;

class TableCellIcon extends TableCell
{
    use HasUrl;
    use HasColor;

    protected string $view = 'chief-table::cells.icon';
}
