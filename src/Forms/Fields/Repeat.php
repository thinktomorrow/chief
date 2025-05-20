<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use DeepCopy\DeepCopy;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Exceptions\RepeatItemsCannotBeLocalized;
use Thinktomorrow\Chief\Forms\Fields\Locales\LocalizedField;
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

    public function components(array $components): static
    {
        $this->components = $components;

        $this->ensureItemsAreNotLocalized();

        return $this;
    }

    public function addComponent($component): void
    {
        $this->components[] = $component;

        $this->ensureItemsAreNotLocalized();
    }

    private function ensureItemsAreNotLocalized(): void
    {
        foreach (Fields::make($this->components)->all() as $component) {
            if ($component instanceof LocalizedField && $component->hasLocales()) {
                throw new RepeatItemsCannotBeLocalized('
                    Repeat items cannot be localized. Localize the repeat field itself.');
            }
        }
    }

    /**
     * getValue retrieves for the current locale, this method allows to fetch the entire
     * repeat values for all locales. The entire json data object as array
     */
    public function getAllValues(): array
    {
        $model = $this->getModel();

        if (method_exists($model, 'isDynamic') && $model->isDynamic($this->getColumnName())) {
            $values = $model->dynamic($this->getColumnName(), null, []);

            return is_array($values) ? $values : [];
        }

        return $model->{$this->getColumnName()} ?: [];
    }
}
