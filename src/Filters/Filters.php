<?php

namespace Thinktomorrow\Chief\Filters;

use ArrayIterator;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Filters implements \ArrayAccess, \IteratorAggregate
{
    /** @var array */
    private $filters;

    public function __construct(array $filters = [])
    {
        $filters = $this->sanitizeFilters($filters);
        $this->validateFilters($filters);

        $this->filters = $filters;
    }

    public function all(): array
    {
        return $this->filters;
    }

    public function apply(Builder $builder)
    {
        foreach ($this->all() as $filter) {
            if (request()->filled($filter->name)) {
                $builder->tap($filter->apply(request()->input($filter->name, null)));
            }
        }
    }

    public function anyApplied(): bool
    {
        foreach ($this->all() as $filter) {
            if (request()->filled($filter->name)) {
                return true;
            }
        }

        return false;
    }

    public function render(): string
    {
        $requestInput = request()->all();

        return array_reduce($this->all(), function ($carry, Filter $filter) use ($requestInput) {
            return $carry . $filter->render($requestInput);
        }, '');
    }

    public function any(): bool
    {
        return count($this->all()) > 0;
    }

    public function isEmpty(): bool
    {
        return !$this->any();
    }

    public function keys(): array
    {
        return array_map(function (Filter $filter) {
            return $filter->key();
        }, $this->filters);
    }

    private function validateFilters(array $filters)
    {
        array_map(function (Filter $filter) {
        }, $filters);
    }

    public function add(Filter ...$filter)
    {
        $this->filters = array_merge($this->filters, $filter);

        return $this;
    }

    public function offsetExists($offset)
    {
        return isset($this->filters[$offset]);
    }

    public function offsetGet($offset)
    {
        return (isset($this->filters[$offset]))
            ? $this->filters[$offset]
            : null;
    }

    public function offsetSet($offset, $value)
    {
        if (! $value instanceof Filter) {
            throw new \InvalidArgumentException('Passed value must be of type ' . Filter::class);
        }

        $this->filters[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->filters[$offset]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->filters);
    }

    private function sanitizeFilters(array $filters)
    {
        return array_map(function ($filter) {
            if (is_string($filter) && class_exists($filter)) {
                return $filter::init();
            }

            return $filter;
        }, $filters);
    }
}
