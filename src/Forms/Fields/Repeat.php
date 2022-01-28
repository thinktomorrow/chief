<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use DeepCopy\DeepCopy;
use Thinktomorrow\Chief\Forms\Fields;

class Repeat extends Component implements Field
{
    protected string $view = 'chief-forms::fields.repeat.repeat';
    protected string $windowView = 'chief-forms::fields.repeat-window';

    public function getRepeatedComponents(?string $locale = null): array
    {
        // Loop over fields and populate them with the value...

        // Group every components stack into a 'card' component. In order to group it.
        // Multiply it with the values - keep in mind the startWithAmount value
        // Populate the fields of each group with the values given.
        $components = [];

        foreach ($this->getActiveValue($locale) ?? [[]] as $index => $values) {
            $components[] = $this->getRepeatCard($index, $values, $locale);
        }

        return $components;
    }

    public function getRepeatCard(int $index, array $values = [], ?string $locale = null): array
    {
        $clonedComponents = (new DeepCopy())
            ->skipUncloneable()
            ->copy($this->components);

        // Populate fields with the correct name and the given values
        Fields::extract($clonedComponents)
            ->each(function ($field) use ($index, $locale, $values) {
                $fieldName = Fields\Locale\LocalizedFormKey::make()
                    ->template(':prefix['.$index.'].:name.:locale')
                    ->replace('prefix', $this->getName())
                    ->bracketed()
                    ->get($field->getName(), $locale)
                ;

                $field->name($fieldName)
                    ->value($values[$field->getColumnName()] ?? null)
                ;
            })
        ;

        return $clonedComponents;
    }
}
