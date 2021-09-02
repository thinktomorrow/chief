<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use ArrayIterator;
use Illuminate\Support\Collection;

class Fields implements \ArrayAccess, \IteratorAggregate, \Countable
{
    public const PAGE_TITLE_TAG = 'chief-page-title';

    private Collection $fieldSets;
    private Collection $fieldWindows;

    final public function __construct(array $fieldSets = [], ?Collection $fieldWindows = null)
    {
        $this->fieldWindows = $fieldWindows ?: collect([]);

        $fieldSets = $this->structureFieldSets($fieldSets);

        $this->validateFieldSets($fieldSets);
        $this->fieldSets = collect($fieldSets);

        $this->cleanupEmptyValues();
    }

    /**
     * @return static
     */
    public static function make(iterable $generator): self
    {
        if ($generator instanceof Fields) {
            return $generator;
        }

        $values = [];

        foreach ($generator as $fieldSet) {
            if (! $fieldSet instanceof FieldSet && is_iterable($fieldSet)) {
                $values = array_merge($values, [...$fieldSet]);
            } else {
                $values[] = $fieldSet;
            }
        }

        return new static($values);
    }

    public function filterByWindowId(string $windowId): Fields
    {
        if ('default' === $windowId) {
            return $this->onlyFieldsWithoutWindow()->untagged(static::PAGE_TITLE_TAG);
        }

        if ($windowId === static::PAGE_TITLE_TAG) {
            return $this->tagged(static::PAGE_TITLE_TAG);
        }

        if ($this->findWindow($windowId)) {
            return $this->findWindow($windowId)->getFields();
        }

        return new Fields();
    }

    public function add(FieldSet ...$fieldSets): Fields
    {
        return new static($this->fieldSets->merge($fieldSets)->all(), $this->fieldWindows);
    }

    public function merge(Fields $fields): Fields
    {
        $purgedFieldSets = $this->remove($fields->keys())->all();

        return new static($purgedFieldSets->merge($fields->all())->all(), $this->fieldWindows);
    }

    public function all(): Collection
    {
        return $this->fieldSets;
    }

    public function allFields(): Collection
    {
        $fields = collect();

        $this->fieldSets->each(function ($fieldSet) use (&$fields) {
            $fields = $fields->merge($fieldSet->all());
        });

        return $fields;
    }

    /**
     * Populate the windows with their Fields.
     */
    public function allWindows(): Collection
    {
        foreach ($this->fieldWindows as $index => $fieldWindow) {
            foreach ($this->fieldSets as $fieldSet) {
                if (in_array($fieldSet->getId(), $fieldWindow->getFieldSetIds())) {
                    $this->fieldWindows[$index] = $this->fieldWindows[$index]->addFieldSet($fieldSet);
                }
            }
        }

        return $this->fieldWindows;
    }

    public function findWindow(string $windowId): ?FieldWindow
    {
        return $this->allWindows()->first(fn ($window) => $window->getId() === $windowId);
    }

    public function getWindowsByPosition(string $position): Collection
    {
        return $this->allWindows()->filter(fn ($window) => $window->getPosition() === $position);
    }

    public function onlyFieldsWithoutWindow(): Fields
    {
        $fieldSetIds = $this->allWindows()->reduce(function ($carry, FieldWindow $window) {
            return array_merge($carry, $window->getFieldSetIds());
        }, []);

        return new static($this->fieldSets->reject(fn ($fieldSet) => in_array($fieldSet->getId(), $fieldSetIds))->all(), $this->fieldWindows);
    }

    public function first(): ?Field
    {
        if ($this->fieldSets->isEmpty()) {
            return null;
        }

        return $this->fieldSets->first()->first();
    }

    public function find(string $key): Field
    {
        foreach ($this->fieldSets as $fieldSet) {
            if ($field = $fieldSet->find($key)) {
                return $field;
            }
        }

        throw new \InvalidArgumentException('No field found by key '.$key);
    }

    public function any(): bool
    {
        return ! $this->fieldSets->isEmpty();
    }

    public function isEmpty(): bool
    {
        return $this->fieldSets->isEmpty();
    }

    public function keys(): array
    {
        $fieldKeys = [];

        $this->fieldSets->each(function ($fieldSet) use (&$fieldKeys) {
            $fieldKeys = array_merge($fieldKeys, $fieldSet->keys());
        });

        return $fieldKeys;
    }

    public function mapFields(callable $callback): Fields
    {
        return $this->map(fn ($fieldSet) => $fieldSet->map($callback));
    }

    /**
     * @param \Closure|string $key
     * @param null|mixed      $value
     *
     * @return static
     */
    public function filterBy($key, $value = null): self
    {
        return new static($this->fieldSets->map(function ($fieldSet) use ($key, $value) {
            return $fieldSet->filterBy($key, $value);
        })->all(), $this->fieldWindows);
    }

    public function model($model): self
    {
        return $this->map(function ($fieldSet) use ($model) {
            return $fieldSet->map(function ($field) use ($model) {
                return $field->model($model);
            });
        });
    }

    public function keyed($key): Fields
    {
        $keys = (array) $key;

        return $this->filterBy(function (Field $field) use ($keys) {
            return in_array($field->getKey(), $keys);
        });
    }

    public function tagged($tag): Fields
    {
        return $this->filterBy(function (Field $field) use ($tag) {
            return $field->tagged($tag);
        });
    }

    public function notTagged($tag): Fields
    {
        return $this->filterBy(function (Field $field) use ($tag) {
            return ! $field->tagged($tag);
        });
    }

    public function untagged(): Fields
    {
        return $this->filterBy(function (Field $field) {
            return $field->untagged();
        });
    }

    public function remove($keys = null): Fields
    {
        return $this->filterBy(function (Field $field) use ($keys) {
            return ! in_array($field->getKey(), $keys);
        });
    }

    public function removeFieldSet(string $fieldSetId): Fields
    {
        $fieldSets = $this->all();

        foreach ($fieldSets as $index => $existingFieldSet) {
            if ($existingFieldSet->getId() === $fieldSetId) {
                unset($fieldSets[$index]);
            }
        }

        return new static($fieldSets->all(), $this->fieldWindows);
    }

    public function offsetExists($offset)
    {
        return isset($this->fieldSets[$offset]);
    }

    public function offsetGet($offset)
    {
        if (! isset($this->fieldSets[$offset])) {
            throw new \RuntimeException('No fieldSet found by key ['.$offset.']');
        }

        return $this->fieldSets[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (! $value instanceof FieldSet) {
            throw new \InvalidArgumentException('Passed value must be of type '.FieldSet::class);
        }

        $this->fieldSets[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->fieldSets[$offset]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fieldSets);
    }

    public function count()
    {
        return count($this->fieldSets);
    }

    private function map(callable $callback): Fields
    {
        return new static($this->fieldSets->map($callback)->all(), $this->fieldWindows);
    }

    /**
     * Add fields that aren't in a fieldSet, inside their own FieldSet.
     */
//    private function giveLonelyFieldsAHome(array $fieldSets): array
//    {
//        $result = [];
//
//        foreach ($fieldSets as $fieldSet) {
//           if ($fieldSet instanceof Field) {
//                $result[] = new FieldSet([$fieldSet]);
//            } else {
//                $result[] = $fieldSet;
//            }
//        }
//
//        return $result;
//    }

    private function validateFieldSets(array $fieldSets): void
    {
        array_map(fn (FieldSet $fieldSet) => $fieldSet, $fieldSets);
    }

    private function structureFieldSets(array $fieldSets): array
    {
        $result = [];
        $openFieldWindowId = false;
        $openFieldSetIndex = false;

        foreach ($fieldSets as $fieldSet) {
            // A fieldWindow is added to our list of windows and no longer included in the array of fieldSets
            if ($fieldSet instanceof FieldWindow) {
                $this->fieldWindows->push($fieldSet);
                $openFieldWindowId = $fieldSet->isOpen()
                    ? $fieldSet->getId()
                    : false;
            } elseif ($fieldSet instanceof FieldSet) {
                // Add this fieldSet to an open window
                if (false !== $openFieldWindowId) {
                    $indexKey = $this->fieldWindows->search(fn ($window) => $window->getId() === $openFieldWindowId);
                    $this->fieldWindows[$indexKey] = $this->fieldWindows[$indexKey]->addFieldSetId($fieldSet->getId());
                }

                $result[] = $fieldSet;

                if ($fieldSet->isOpen()) {
                    $openFieldSetIndex = array_key_last($result);
                } else {
                    $openFieldSetIndex = false;
                }
            }

            // Give lonely fields as fieldSet home
            elseif ($fieldSet instanceof Field) {
                // Is fieldSet open?
                if (false !== $openFieldSetIndex) {
                    $result[$openFieldSetIndex] = $result[$openFieldSetIndex]->add($fieldSet);
                } else {
                    $fieldSet = FieldSet::make([$fieldSet]);
                    $openFieldSetIndex = false;

                    if (false !== $openFieldWindowId) {
                        $indexKey = $this->fieldWindows->search(fn ($window) => $window->getId() === $openFieldWindowId);
                        $this->fieldWindows[$indexKey] = $this->fieldWindows[$indexKey]->addFieldSetId($fieldSet->getId());
                    }

                    $result[] = $fieldSet;
                }
            } else {
                throw new \InvalidArgumentException('Only FieldSet instances should be passed.');
            }
        }

        return $result;
    }

    private function cleanupEmptyValues(): void
    {
        foreach ($this->fieldSets as $k => $fieldSet) {
            if ($fieldSet->isEmpty()) {
                unset($this->fieldSets[$k]);
                // TODO: remove from fieldWindow as well? Not required but is cleaner
            }
        }

        foreach ($this->fieldWindows as $k => $fieldWindow) {
            if ($fieldWindow->isEmpty()) {
                unset($this->fieldWindows[$k]);
            }
        }
    }
}
