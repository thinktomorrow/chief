<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Filters;

use ArrayIterator;
use Illuminate\Database\Eloquent\Builder;
use Thinktomorrow\Chief\Forms\Fields\Field;

class Filters implements \ArrayAccess, \IteratorAggregate, \Countable
{
    /** @var array */
    private $filters;

    public function __construct(array $filters = [])
    {
        $this->validateFilters($filters);

        $this->filters = $this->sanitizeFilters($filters);
    }

    /**
     * @return static
     */
    public static function make(iterable $generator): self
    {
        $filters = new static();

        foreach ($generator as $filter) {
            if (is_iterable($filter)) {
                $filters = $filters->add(...$filter);
            } else {
                $filters = $filters->add($filter);
            }
        }

        return $filters;
    }

    public function all(): array
    {
        return $this->filters;
    }

    public function allApplicable(array $parameterBag): static
    {
        $applicableFilters = [];

        foreach ($this->filters as $filter) {
            if ($filter->applicable($parameterBag)) {
                $applicableFilters[] = $filter;
            }
        }

        return new static($applicableFilters);
    }

    public function apply(Builder $builder, array $parameterBag): void
    {
        foreach ($this->all() as $filter) {
            if ($filter->applicable($parameterBag)) {
                $filter->query($builder, $parameterBag);
            }
        }
    }

    public function render(array $parameterBag): string
    {
        return array_reduce($this->all(), function ($carry, Filter $filter) use($parameterBag) {
            return $carry . $filter->render($parameterBag);
        }, '');
    }

    public function any(): bool
    {
        return count($this->all()) > 0;
    }

    public function anyRenderable(): bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        // If at least one of the filters has content to be rendered.
        foreach ($this->filters as $filter) {
            if ($filter->render([])) {
                return true;
            }
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return ! $this->any();
    }

    private function validateFilters(array $filters): void
    {
        foreach($filters as $filter) {
            if(!$filter instanceof Filter) {
                throw new \InvalidArgumentException('Filters class accepts instances of ' . Filter::class.'. [' . $filter::class . '] was given instead.');
            }
        }
    }

    private function sanitizeFilters(array $filters): array
    {
        $existingQueryKeys = [];

        foreach (array_reverse($filters, true) as $index => $filter) {
            if (in_array($filter->queryKey(), $existingQueryKeys)) {
                unset($filters[$index]);

                continue;
            }

            $existingQueryKeys[] = $filter->queryKey();
        }

        return array_values($filters);
    }

    public function add(Filter ...$filter): Filters
    {
        return $this->merge(new Filters($filter));
    }

    public function merge(Filters $other): Filters
    {
        return new static(array_merge($this->filters, $other->all()));
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->filters);
    }

    public function count(): int
    {
        return count($this->filters);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->filters[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        if (! isset($this->filters[$offset])) {
            throw new \RuntimeException('No filter found by key ['.$offset.']');
        }

        return $this->filters[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (! $value instanceof Filter) {
            throw new \InvalidArgumentException('Passed value must be of type '.Filter::class);
        }

        $this->filters[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->filters[$offset]);
    }
}
