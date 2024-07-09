<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\TableNew\Filters;

use Closure;
use Thinktomorrow\Chief\Forms\Fields\Concerns\Select\PairOptions;

class OptionFilter extends Filter
{
    private string $displayType = 'select';
    private array $options = [];
    private bool $multiple = false;

    public static function make(string $queryKey, Closure $query): self
    {
        $filter = new static($queryKey, $query);
        $filter->displayAsSelect();

        return $filter;
    }

    public function displayAsRadio(): self
    {
        $this->displayType = 'radio';
        $this->view('chief-table-new::filters.radio');

        return $this;
    }

    public function displayAsCheckbox(): self
    {
        $this->displayType = 'checkbox';
        $this->view('chief-table-new::filters.checkbox');

        return $this;
    }

    public function displayAsSelect(): self
    {
        $this->displayType = 'select';
        $this->view('chief-table-new::filters.select');

        return $this;
    }

    public function displayAsToggle(): self
    {
        $this->displayType = 'toggle';
        $this->view('chief-table-new::filters.toggle');

        return $this;
    }

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function multiple(bool $multiple = true): self
    {
        $this->multiple = $multiple;

        return $this;
    }

    protected function viewData(): array
    {
        return array_merge(parent::viewData(), [
            'options' => $this->displayType == 'select' ? PairOptions::toMultiSelectPairs($this->options) : $this->options,
            'multiple' => $this->multiple,
        ]);
    }
}
