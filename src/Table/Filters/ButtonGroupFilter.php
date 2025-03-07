<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;

class ButtonGroupFilter extends Filter
{
    use HasMultiple;
    use HasOptions;

    protected string $view = 'chief-table::filters.button-group';
}
