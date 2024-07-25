<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters;

use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;

class ButtonGroupFilter extends Filter
{
    use HasOptions;
    use HasMultiple;

    protected string $view = 'chief-table-new::filters.button-group';
}
