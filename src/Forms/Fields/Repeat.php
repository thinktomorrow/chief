<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Fields;

use DeepCopy\DeepCopy;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Managers\Manager;

class Repeat extends Component implements Field
{
    use Fields\Concerns\HasEndpoint;

    protected string $view = 'chief-form::fields.repeat.repeat';
    protected string $sectionView = 'chief-form::fields.repeat.repeat-section';
    protected string $windowView = 'chief-form::fields.repeat.repeat-window';

    public function getRepeatedComponents(?string $locale = null): array
    {
        // Loop over fields and populate them with the value...

        // Group every component stack into a repeat-section
        // Multiply it with the values - keep in mind the startWithAmount value
        // Populate the fields of each group with the values given.
        $components = [];

        foreach ($this->getActiveValue($locale) ?? [[]] as $index => $values) {
            $components[] = $this->getRepeatSection($index, $values, $locale);
        }

        return $components;
    }

    public function getRepeatSection(int $index, array $values = [], ?string $locale = null): array
    {
        $clonedComponents = (new DeepCopy())
            ->skipUncloneable()
            ->copy($this->components)
        ;

        // Populate fields with the correct name and the given values
        Fields::make($clonedComponents, fn ($field) => ! $field instanceof self)
            ->each(function (Field $field) use ($index, $locale, $values) {
                $fieldName = Common\LocalizedFormKey::make()
                    ->template(':prefix.'.$index.'.:name.:locale')
                    ->replace('prefix', $this->getName())
                    ->bracketed()
                    ->get($field->getName(), $locale)
                ;

                $field->name($fieldName)
                    ->value($values[$field->getColumnName()] ?? null)
                    ->setLocalizedFormKeyTemplate(':name.:locale')
                ;
            })
        ;

        return $clonedComponents;
    }

    public function fill(Manager $manager, Model $model): void
    {
        $this->endpoint($manager->route('repeat-section', [$this->getKey(), $model->id]));
    }

    public function sectionView(string $view): static
    {
        $this->sectionView = $view;

        return $this;
    }

    public function getSectionView(): string
    {
        return $this->sectionView;
    }
}
