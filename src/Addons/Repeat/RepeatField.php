<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Addons\Repeat;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\ManagedModels\Fields\Field;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\FieldSet;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\AbstractField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FieldType;

class RepeatField extends AbstractField implements Field
{
    private ?Fields $fields = null;

    public static function make(string $key, iterable $fields): Field
    {
        return (new static(new FieldType('repeat'), $key))
            ->setFields(Fields::make($fields))
            ->view('chief-addon-repeat::repeat')
            ->windowView('chief-addon-repeat::window');
    }

    /**
     * Get the fieldSet as a clone so it can be repeated.
     * The clone element should / could be removed once the Field object is IMMUTABLE.
     */
    public function getFieldSet(): FieldSet
    {
        return $this->cloneFieldSet();
    }

    public function getRepeatedFields(): Fields
    {
        $existingFieldSets = [];

        if (!($values = $this->getValue()) || !is_array($values)) {
            $values = [null];
        }

        foreach ($values as $index => $value) {
            $existingFieldSets[] = $this->getFieldSet()->map(function (Field $field) use ($index, $value) {
                return $field->name($this->getName().'['.$index.']['.$field->getName().']')
                    ->localizedFormat(':name.:locale')
                    ->valueResolver(function ($model = null, $locale = null, $field) use ($value) {
                        if (!$value || !isset($value[$field->getColumn()])) {
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

        return Fields::make($existingFieldSets);
    }

    // TEMP: this should be removed once the Field object is IMMUTABLE
    private function cloneFieldSet(): FieldSet
    {
        $fields = [];

        foreach ($this->fields->allFields() as $key => $field) {
            $fields[$key] = clone $field;
        }

        return FieldSet::make($fields);
    }

    private function setFields(Fields $fields): Field
    {
        $this->fields = $fields;

        return $this;
    }
}
