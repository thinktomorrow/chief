<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use Illuminate\Database\Eloquent\Builder;

class Filters
{
    /** @var array */
    private $filters;

    public function __construct(array $filters = [])
    {
        $this->validateFilters($filters);

        $this->filters = $this->sanitizeFilters($filters);
    }

    public function all(): array
    {
        return $this->filters;
    }

    public function apply(Builder $builder)
    {
        foreach ($this->all() as $filter) {
            if ($filter->applicable(request())) {
                $builder->tap($filter->query($builder));
            }
        }
    }

    public function render(): string
    {
        return array_reduce($this->all(), function ($carry, Filter $filter) {
            return $carry . $filter->render();
        }, '');
    }

    public function any(): bool
    {
        return count($this->all()) > 0;
    }

    public function anyRenderable(): bool
    {
        if($this->isEmpty()) return false;

        // If at least one of the filters has content to be rendered.
        foreach($this->filters as $filter) {
            if($filter->render()) {
                return true;
            }
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return !$this->any();
    }

    public function keys(): array
    {
        return array_map(function (AbstractFilter $filter) {
            return $filter->key();
        }, $this->filters);
    }

    private function validateFilters(array $filters)
    {
        array_map(function (Filter $filter) {}, $filters);
    }

    private function sanitizeFilters(array $filters): array
    {
        $existingQueryKeys = [];

        foreach(array_reverse($filters, true) as $index => $filter) {

            if(in_array($filter->queryKey(), $existingQueryKeys)) {
                unset($filters[$index]);
                continue;
            }

            $existingQueryKeys[] = $filter->queryKey();
        }

        return $filters;
    }

    public function add(Filter ...$filter): Filters
    {
        return $this->merge(new Filters($filter));
    }

    public function merge(Filters $other): Filters
    {
        return new static(array_merge($this->filters, $other->all()));
    }

}
