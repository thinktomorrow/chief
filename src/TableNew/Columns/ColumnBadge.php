<?php

namespace Thinktomorrow\Chief\TableNew\Columns;

use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValue;
use Thinktomorrow\Chief\TableNew\Concerns\HasType;

class ColumnBadge extends ColumnText
{
    use HasType;

    protected string $view = 'chief-table-new::columns.badge';
}
