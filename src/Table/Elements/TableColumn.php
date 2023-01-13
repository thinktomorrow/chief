<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Elements;

use Thinktomorrow\Chief\Table\Concerns\HasValue;

class TableColumn extends Component
{
    use HasValue;

    protected string $view = 'chief-table::cells.text';
}
