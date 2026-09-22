<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table\Filters;

use Closure;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasGroupedOptions;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasMultiple;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\HasOptions;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;

class SelectFilter extends Filter
{
    use HasGroupedOptions;
    use HasMultiple;
    use HasOptions {
        getOptions as private resolveOptions;
        options as private setOptions;
    }

    private array $memoizedOptions = [];

    protected string $view = 'chief-table::filters.select';

    public function options(array|Closure $options, bool $sanitize = true): static
    {
        $this->memoizedOptions = [];

        return $this->setOptions($options, $sanitize);
    }

    public function getOptions(?string $locale = null): array
    {
        $key = serialize([
            $locale,
            app()->getLocale(),
            $this->getTableFilters(),
        ]);

        return $this->memoizedOptions[$key] ??= $this->resolveOptions($locale);
    }

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

    public function shouldInitiallyRender(): bool
    {
        return $this->getOptions() !== [];
    }

    /**
     * Pass the active table filters to dynamic option callbacks.
     */
    private function getOptionsCallableParameters(?string $locale = null): array
    {
        return [$this, $locale, $this->getTableFilters()];
    }
}
