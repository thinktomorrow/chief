<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Addons\Repeat;

use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Types\AbstractField;
use Thinktomorrow\Chief\Forms\Fields\Types\FieldType;

class RepeatField extends AbstractField implements Field
{
    private ?Fields $fields = null;
    private bool $prefersCompactLayout = false;

    public static function make(string $key, iterable $fields): Field
    {
        return (new static(new FieldType('repeat'), $key))
            ->setFields(Fields::make($fields))
            ->view('chief-addon-repeat::repeat')
            ->windowView('chief-addon-repeat::window');
    }

    /**
     * Get the fields as a clone so it can be repeated per existing entry
     * The clone element should / could be removed once the Field object is IMMUTABLE.
     */
    public function getFields(): Fields
    {
        return $this->cloneFields();
    }

    public function getRepeatedFields(): array
    {
        $existingFields = [];

        if (! ($values = $this->getValue()) || ! is_array($values)) {
            $values = [null];
        }

        foreach ($values as $index => $value) {
            $existingFields[] = $this->getFields()->map(function (Field $field) use ($index, $value) {
                return $field->name($this->getName().'['.$index.']['.$field->getName().']')
                    ->localizedFormat(':name.:locale')
                    ->valueResolver(function ($model = null, $locale = null, $field) use ($value) {
                        if (! $value || ! isset($value[$field->getColumn()])) {
                            return null;
                        }

                        $result = $value[$field->getColumn()];

                        if ($field->isLocalized() && is_array($result)) {
                            return array_key_exists($locale = $locale ?? app()->getLocale(), $result)
                                ? $result[$locale]
                                : null;
                        }

                        return $result;
                    })
                ;
            });
        }

        return $existingFields;
    }

    // TODO: once the Field object is IMMUTABLE this is no longer needed and should be removed
    private function cloneFields(): Fields
    {
        $fields = [];

        foreach ($this->fields->all() as $key => $field) {
            $fields[$key] = clone $field;
        }

        return Fields::make($fields);
    }

    private function setFields(Fields $fields): self
    {
        $this->fields = $fields;

        return $this;
    }

    public function preferCompactLayout(): self
    {
        $this->prefersCompactLayout = true;

        return $this;
    }

    public function prefersCompactLayout(): bool
    {
        return $this->prefersCompactLayout;
    }
}
