<?php

namespace Thinktomorrow\Chief\Table\Columns;

use Thinktomorrow\Chief\Table\Columns\Concerns\HasType;

class ColumnBadge extends ColumnText
{
    use HasType;

    protected string $view = 'chief-table::columns.badge';
}
