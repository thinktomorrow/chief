<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Table\Concerns\HasSortable;

class TableColumn extends Component
{
    use HasSortable;

    protected string $view = 'chief-table::table-head';
}

