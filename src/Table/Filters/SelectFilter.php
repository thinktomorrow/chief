<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;

class SelectFilter extends Filter
{
    use HasMultiple;
    use HasOptions;

    protected string $view = 'chief-table::filters.select';

    public function getMultiSelectFieldOptions(?string $locale = null): array
    {
        return PairOptions::convertOptionsToChoices($this->getOptions($locale));
    }

    private function getOptionsCallableParameters(?string $locale = null): array
    {
        return [$this, $locale];
    }
}
