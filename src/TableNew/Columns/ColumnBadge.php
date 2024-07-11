<?php

namespace Thinktomorrow\Chief\TableNew\Columns;

use Thinktomorrow\Chief\TableNew\Columns\Concerns\HasType;

class ColumnBadge extends ColumnText
{
    use HasType;

    protected string $view = 'chief-table-new::columns.badge';
}
