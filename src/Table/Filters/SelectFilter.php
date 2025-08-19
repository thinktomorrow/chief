<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasGroupedOptions;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;

class SelectFilter extends Filter
{
    use HasGroupedOptions;
    use HasMultiple;
    use HasOptions;

    protected string $view = 'chief-table::filters.select';

    public function getMultiSelectFieldOptions(?string $locale = null): array
    {
        return PairOptions::convertOptionsToChoices($this->getOptions($locale));
    }

    public function findLabelByValue(string $value, ?string $locale = null): ?string
    {
        $options = $this->getOptions($locale);

        if ($this->hasOptionGroups($locale)) {
            foreach ($options as $group) {
                foreach ($group['options'] as $option) {
                    if ($option['value'] == $value) {
                        return $option['label'];
                    }
                }
            }

            return null;
        }

        foreach ($options as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }

        return null;
    }

    private function getOptionsCallableParameters(?string $locale = null): array
    {
        return [$this, $locale];
    }
}
