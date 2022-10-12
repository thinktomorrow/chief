<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Forms\Concerns\HasLayoutType;

class TableCellLabel extends TableCell
{
    use HasLayoutType;

    protected string $view = 'chief-table::cells.label';
}
