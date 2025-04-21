<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use DeepCopy\DeepCopy;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Tags\HasTaggedComponents;
use Thinktomorrow\Chief\Forms\Tags\WithTaggedComponents;

class Repeat extends Component implements Field, HasTaggedComponents
{
    use WithTaggedComponents;

    protected string $view = 'chief-form::fields.repeat';

    protected string $previewView = 'chief-form::previews.fields.repeat';

    /**
     * Provide the repeated components so that they are easily presented in
     * the admin windows based on their field type of rendering.
     */
    public function getRepeatedComponents(?string $locale = null): array
    {
        $components = [];

        foreach ($this->getValueOrFallback($locale) ?? [[]] as $index => $values) {
            $components[] = $this->getRepeatSection($index, $values, $locale);
        }

        return $components;
    }

    private function getRepeatSection(int $index, array $values = [], ?string $locale = null, ?string $prefix = null): array
    {
        $clonedComponents = (new DeepCopy)
            ->skipUncloneable()
            ->copy($this->components);

        // Populate fields with the correct name and the given values
        Fields::make($clonedComponents, fn ($field) => ! $field instanceof self)
            ->each(function (Field $field) use ($index, $locale, $values, $prefix) {
                $fieldName = FieldName\FieldName::make()
                    ->template(':prefix.'.$index.'.:name.:locale')
                    ->replace('prefix', $prefix ?: $this->getName())
                    ->bracketed()
                    ->get($field->getName(), $locale);

                $field->name($fieldName)
                    ->elementId($field->getElementId().'_'.Str::random())
                    ->value(data_get($values, $field->getColumnName(), null))
                    ->setLocalizedFormKeyTemplate(':name.:locale');
            });

        return $clonedComponents;
    }
}
