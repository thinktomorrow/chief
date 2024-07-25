<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters;

use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;
use Thinktomorrow\Chief\TableNew\Filters\Concerns\HasBlankValue;

class ButtonGroupFilter extends Filter
{
    use HasOptions;
    use HasMultiple;
    use HasBlankValue;

    protected string $view = 'chief-table-new::filters.button-group';

    public function isApplicable($filterValue): bool
    {
        if($this->hasBlankValue() && $filterValue === $this->getBlankValue()) {
            return false;
        }

        return true;
    }
}
