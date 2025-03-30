<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use DeepCopy\DeepCopy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Concerns\HasComponents;
use Thinktomorrow\Chief\Forms\Concerns\WithComponents;
use Thinktomorrow\Chief\Forms\Tags\HasTaggedComponents;
use Thinktomorrow\Chief\Forms\Tags\WithTaggedComponents;
use Thinktomorrow\Chief\Managers\Manager;

class Repeat extends Component implements Field, HasComponents, HasTaggedComponents
{
    use WithComponents;
    use WithTaggedComponents;

    protected string $view = 'chief-form::fields.repeat.repeat';

    protected string $previewView = 'chief-form::fields.repeat.repeat-window';

    /**
     * Provide the repeated components so that they are easily presented in
     * the admin windows based on their field type of rendering.
     */
    public function getRepeatedComponents(?string $locale = null): array
    {
        $components = [];

        foreach ($this->getActiveValue($locale) ?? [[]] as $index => $values) {
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

    public function fill(Manager $manager, Model $model): void
    {
        $this->endpoint($manager->route('repeat-section', [$this->getKey(), $model->id]));
    }

    //    public function sectionView(string $view): static
    //    {
    //        $this->sectionView = $view;
    //
    //        return $this;
    //    }
    //
    //    public function getSectionView(): string
    //    {
    //        return $this->sectionView;
    //    }
}
