<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Fields;

use ArrayIterator;
use Illuminate\Support\Collection;

class Fields implements \ArrayAccess, \IteratorAggregate, \Countable
{
    private Collection $fieldGroups;
    private Collection $fieldWindows;

    final public function __construct(array $fieldGroups = [], ?Collection $fieldWindows = null)
    {
        $this->fieldWindows = $fieldWindows ?: collect([]);

        $fieldGroups = $this->structureFieldGroups($fieldGroups);

        $this->validateFieldGroups($fieldGroups);
        $this->fieldGroups = collect($fieldGroups);

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

        foreach ($generator as $fieldGroup) {
            if (is_iterable($fieldGroup)) {
                $values = array_merge($values, [...$fieldGroup]);
            } else {
                $values[] = $fieldGroup;
            }
        }

        return new static($values);
    }

    public function add(FieldGroup ...$fieldGroups): Fields
    {
        return new static($this->fieldGroups->merge($fieldGroups)->all(), $this->fieldWindows);
    }

    public function merge(Fields $fields): Fields
    {
        $purgedFieldGroups = $this->remove($fields->keys())->all();

        return new static($purgedFieldGroups->merge($fields->all())->all(), $this->fieldWindows);
    }

    public function all(): Collection
    {
        return $this->fieldGroups;
    }

    public function allFields(): Collection
    {
        $fields = collect();

        $this->fieldGroups->each(function ($fieldGroup) use (&$fields) {
            $fields = $fields->merge($fieldGroup->all());
        });

        return $fields;
    }

    /**
     * Populate the windows with their Fields.
     *
     * @return Collection
     */
    public function allWindows(): Collection
    {
        foreach ($this->fieldWindows as $index => $fieldWindow) {
            foreach ($this->fieldGroups as $fieldGroup) {
                if (in_array($fieldGroup->getId(), $fieldWindow->getFieldGroupIds())) {
                    $this->fieldWindows[$index] = $this->fieldWindows[$index]->addFieldGroup($fieldGroup);
                }
            }
        }

        return $this->fieldWindows;
    }

    public function findWindow(string $windowId): ?FieldWindow
    {
        return $this->allWindows()->first(fn ($window) => $window->getId() === $windowId);
    }

    public function onlyFieldsWithoutWindow(): Fields
    {
        $fieldGroupIds = $this->allWindows()->reduce(function ($carry, FieldWindow $window) {
            return array_merge($carry, $window->getFieldGroupIds());
        }, []);

        return new static($this->fieldGroups->reject(fn ($fieldGroup) => in_array($fieldGroup->getId(), $fieldGroupIds))->all(), $this->fieldWindows);
    }

    public function first(): ?Field
    {
        if ($this->fieldGroups->isEmpty()) {
            return null;
        }

        return $this->fieldGroups->first()->first();
    }

    public function find(string $key): Field
    {
        foreach ($this->fieldGroups as $fieldGroup) {
            if ($field = $fieldGroup->find($key)) {
                return $field;
            }
        }

        throw new \InvalidArgumentException('No field found by key '.$key);
    }

    public function any(): bool
    {
        return ! $this->fieldGroups->isEmpty();
    }

    public function isEmpty(): bool
    {
        return $this->fieldGroups->isEmpty();
    }

    public function keys(): array
    {
        $fieldKeys = [];

        $this->fieldGroups->each(function ($fieldGroup) use (&$fieldKeys) {
            $fieldKeys = array_merge($fieldKeys, $fieldGroup->keys());
        });

        return $fieldKeys;
    }

    public function mapFields(callable $callback): Fields
    {
        return $this->map(fn ($fieldGroup) => $fieldGroup->map($callback));
    }

    /**
     * @param \Closure|string $key
     * @param null|mixed      $value
     *
     * @return static
     */
    public function filterBy($key, $value = null): self
    {
        return new static($this->fieldGroups->map(function ($fieldGroup) use ($key, $value) {
            return $fieldGroup->filterBy($key, $value);
        })->all(), $this->fieldWindows);
    }

    public function model($model): self
    {
        return $this->map(function ($fieldGroup) use ($model) {
            return $fieldGroup->map(function ($field) use ($model) {
                return $field->model($model);
            });
        });
    }

    public function component($componentKey): Fields
    {
        return $this->filterBy(function ($field) use ($componentKey) {
            return $field->componentKey() === $componentKey;
        });
    }

//    public function groupByComponent(): Fields
//    {
//        $fields = new static();
//
//        foreach ($this->allFields() as $field) {
//            if (!isset($fields[$field->componentKey()])) {
//                $fields[$field->componentKey()] = new FieldGroup();
//            }
//
//            $fields[$field->componentKey()] = $fields[$field->componentKey()]->add($field);
//        }
//
//        return $fields;
//    }

//    public function render(): string
//    {
//        return $this->fieldGroups->reduce(function (string $carry, Field $field) {
//            return $carry . $field->render();
//        }, '');
//    }

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

    public function removeFieldGroup(string $fieldGroupId): Fields
    {
        $fieldGroups = $this->all();

        foreach ($fieldGroups as $index => $existingFieldGroup) {
            if ($existingFieldGroup->getId() === $fieldGroupId) {
                unset($fieldGroups[$index]);
            }
        }

        return new static($fieldGroups->all(), $this->fieldWindows);
    }

    public function offsetExists($offset)
    {
        return isset($this->fieldGroups[$offset]);
    }

    public function offsetGet($offset)
    {
        if (! isset($this->fieldGroups[$offset])) {
            throw new \RuntimeException('No fieldgroup found by key ['.$offset.']');
        }

        return $this->fieldGroups[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (! $value instanceof FieldGroup) {
            throw new \InvalidArgumentException('Passed value must be of type '.FieldGroup::class);
        }

        $this->fieldGroups[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->fieldGroups[$offset]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->fieldGroups);
    }

    public function count()
    {
        return count($this->fieldGroups);
    }

    private function map(callable $callback): Fields
    {
        return new static($this->fieldGroups->map($callback)->all(), $this->fieldWindows);
    }

    /**
     * Add fields that aren't in a fieldGroup, inside their own FieldGroup.
     */
//    private function giveLonelyFieldsAHome(array $fieldGroups): array
//    {
//        $result = [];
//
//        foreach ($fieldGroups as $fieldGroup) {
//           if ($fieldGroup instanceof Field) {
//                $result[] = new FieldGroup([$fieldGroup]);
//            } else {
//                $result[] = $fieldGroup;
//            }
//        }
//
//        return $result;
//    }

    private function validateFieldGroups(array $fieldGroups): void
    {
        array_map(fn (FieldGroup $fieldGroup) => $fieldGroup, $fieldGroups);
    }

    private function structureFieldGroups(array $fieldGroups): array
    {
        $result = [];
        $openFieldWindowId = false;
        $openFieldGroupIndex = false;

        foreach ($fieldGroups as $fieldGroup) {
            // A fieldWindow is added to our list of windows and no longer included in the array of fieldgroups
            if ($fieldGroup instanceof FieldWindow) {
                if ($fieldGroup->isOpen()) {
                    $openFieldWindowId = $fieldGroup->getId();
                    $this->fieldWindows->push($fieldGroup);
                } else {
                    $openFieldWindowId = false;
                }
            } elseif ($fieldGroup instanceof FieldGroup) {
                // Add this fieldgroup to an open window
                if (false !== $openFieldWindowId) {
                    $indexKey = $this->fieldWindows->search(fn ($window) => $window->getId() === $openFieldWindowId);
                    $this->fieldWindows[$indexKey] = $this->fieldWindows[$indexKey]->addFieldGroupId($fieldGroup->getId());
                }

                $result[] = $fieldGroup;

                if ($fieldGroup->isOpen()) {
                    $openFieldGroupIndex = array_key_last($result);
                } else {
                    $openFieldGroupIndex = false;
                }
            }

            // Give lonely fields as fieldGroup home
            elseif ($fieldGroup instanceof Field) {
                // Is fieldgroup open?
                if (false !== $openFieldGroupIndex) {
                    $result[$openFieldGroupIndex] = $result[$openFieldGroupIndex]->add($fieldGroup);
                } else {
                    $fieldGroup = FieldGroup::make([$fieldGroup]);

                    if (false !== $openFieldWindowId) {
                        $indexKey = $this->fieldWindows->search(fn ($window) => $window->getId() === $openFieldWindowId);
                        $this->fieldWindows[$indexKey] = $this->fieldWindows[$indexKey]->addFieldGroupId($fieldGroup->getId());
                    }

                    $result[] = $fieldGroup;
                }
            } else {
                throw new \InvalidArgumentException('Only FieldGroup instances should be passed.');
            }
        }

        return $result;
    }

    private function cleanupEmptyValues(): void
    {
        foreach ($this->fieldGroups as $k => $fieldGroup) {
            if ($fieldGroup->isEmpty()) {
                unset($this->fieldGroups[$k]);
                // TODO: remove from fieldWindow as well? Not required but is cleaner
            }
        }

        foreach ($this->fieldWindows as $k => $fieldWindow) {
            if ($fieldWindow->isEmpty()) {
                unset($this->fieldWindows[$k]);
            }
        }
    }

    // Add fields that aren't in a fieldGroup, inside their own FieldGroup.
//    private function addLonelyFieldsToOpenFieldGroups(array $fieldGroups): array
//    {
//        $result = [];
//        $lastFieldGroupIndex = null;
//
//        foreach ($fieldGroups as $fieldGroup) {
//            if ($fieldGroup instanceof FieldWindow) {
//                $result[] = $fieldGroup;
//            } elseif ($fieldGroup instanceof FieldGroup) {
//                $result[] = $fieldGroup;
//                $lastFieldGroupIndex = array_key_last($result);
//            } elseif ($fieldGroup instanceof Field) {
//                // If there is an open fieldgroup, we'll add this field to that one dynamically
//                if (null !== $lastFieldGroupIndex && $result[$lastFieldGroupIndex]->isOpen()) {
//                    $result[$lastFieldGroupIndex] = $result[$lastFieldGroupIndex]->add($fieldGroup);
//                } else {
//                    $result[] = new FieldGroup([$fieldGroup]);
//                }
//            } else {
//                throw new \InvalidArgumentException('Only FieldGroup of Field instances should be passed.');
//            }
//        }
//
//        return $result;
//    }
}
