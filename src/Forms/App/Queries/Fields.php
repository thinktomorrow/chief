<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\App\Queries;

use ArrayIterator;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Fields\Common\ResolveIterables;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Tags\HasTaggedComponents;
use Thinktomorrow\Chief\Forms\Tags\WithTaggedComponents;

use function collect;

class Fields implements \ArrayAccess, \Countable, \IteratorAggregate, HasTaggedComponents
{
    use WithTaggedComponents;

    private Collection $items;

    final private function __construct(array $items = [])
    {
        $this->validateFields($items);

        $this->items = collect($items)->mapWithKeys(function ($item) {
            return [$item->getKey() => $item];
        });
    }

    public static function make(iterable $components, ?callable $stopRecursiveCallback = null): static
    {
        return new static(static::extractRecursive(ResolveIterables::resolve($components), $stopRecursiveCallback));
    }

    // Return all fields but omit any nested fields such as there are in the repeat field
    public static function makeWithoutFlatteningNestedFields(iterable $components): static
    {
        return static::make($components, fn ($field) => ! $field instanceof Repeat && ! $field instanceof File);
    }

    public function first(): ?Field
    {
        return $this->items->first();
    }

    public function has(string $key): bool
    {
        return isset($this->items[$key]);
    }

    public function find(string $key): Field
    {
        if (! isset($this->items[$key])) {
            throw new \InvalidArgumentException('No field found by key '.$key);
        }

        return $this->items[$key];
    }

    public function any(): bool
    {
        return count($this->all()) > 0;
    }

    public function isEmpty(): bool
    {
        return ! $this->any();
    }

    public function keys(): array
    {
        return $this->items->keys()->all();
    }

    public function map(callable $callback): self
    {
        return new static($this->items->map($callback)->all());
    }

    public function each(callable $callback): self
    {
        foreach ($this->items as $item) {
            call_user_func($callback, $item);
        }

        return $this;
    }

    /**
     * @param  \Closure|string  $key
     * @param  null|mixed  $value
     * @return static
     */
    public function filterBy($key, $value = null): self
    {
        $fields = [];

        foreach ($this->items as $field) {
            if ($key instanceof \Closure) {
                if ($key($field) == true) {
                    $fields[] = $field;
                }

                continue;
            }

            $method = 'get'.ucfirst($key);

            // Reject from list if value does not match expected one
            if ($value && $value == $field->{$method}()) {
                $fields[] = $field;
            } // Reject from list if key returns null (key not present on field)
            elseif (! $value && ! is_null($field->{$method}())) {
                $fields[] = $field;
            }
        }

        return new static($fields);
    }

    public function add(Field ...$fields): self
    {
        return $this->merge(new static($fields));
    }

    public function merge(self $other): self
    {
        return new static($this->items->merge($other->all())->all());
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        if (! isset($this->items[$offset])) {
            throw new \RuntimeException('No field found by key ['.$offset.']');
        }

        return $this->items[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        if (! $value instanceof Field) {
            throw new \InvalidArgumentException('Passed value must be of type '.Field::class);
        }

        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        // TODO: make immutable...
        unset($this->items[$offset]);
    }

    public function getIterator(): \Traversable
    {
        return new ArrayIterator($this->items->all());
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function all(): Collection
    {
        return $this->items;
    }

    public function model($model): self
    {
        return $this->map(function ($field) use ($model) {
            return $field->model($model);
        });
    }

    public function keyed($key): self
    {
        $keys = (array) $key;

        return $this->filterBy(function (Field $field) use ($keys) {
            return in_array($field->getKey(), $keys);
        });
    }

    //    public function tagged($tag): self
    //    {
    //        if (is_string($tag) && $tag === 'untagged') {
    //            return $this->untagged();
    //        }
    //
    //        return $this->filterBy(function (Field $field) use ($tag) {
    //            return $field->isTagged($tag);
    //        });
    //    }
    //
    //    public function notTagged($tag): self
    //    {
    //        return $this->filterBy(function (Field $field) use ($tag) {
    //            return ! $field->isTagged($tag);
    //        });
    //    }
    //
    //    public function untagged(): self
    //    {
    //        return $this->filterBy(function (Field $field) {
    //            return $field->isUntagged();
    //        });
    //    }

    public function remove(array|string|callable $keys): self
    {
        return $this->filterBy(function (Field $field) use ($keys) {
            if (is_callable($keys)) {
                return ! call_user_func_array($keys, [$field]);
            }

            return ! in_array($field->getKey(), (array) $keys);
        });
    }

    /**
     * The stopRecursiveCallback callable is set for when to stop the recursive when this function returns false.
     * This is used internally for explicitly stop nested fields detection such as a nested repeat field.
     *
     * @param  array  $components
     */
    private static function extractRecursive(iterable $components, ?callable $stopRecursiveCallback = null): array
    {
        $fields = [];

        foreach ($components as $component) {
            if ($component instanceof Field) {
                $fields[] = $component;
            }

            if ($stopRecursiveCallback && call_user_func($stopRecursiveCallback, $component) === false) {
                continue;
            }

            if ($component instanceof HasComponents) {
                $fields = array_merge($fields, static::extractRecursive($component->getComponents(), $stopRecursiveCallback));
            }
        }

        return $fields;
    }

    private function values(): array
    {
        return $this->items->values()->all();
    }

    private function validateFields(array $fields): void
    {
        array_map(function (Field $field) {
            return $field;
        }, $fields);
    }

    public function getComponents(): array
    {
        return $this->items->all();
    }

    public function components(array $components): static
    {
        $this->items = collect($components);

        return $this;
    }
}
