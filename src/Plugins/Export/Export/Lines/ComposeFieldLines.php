<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export\Lines;

use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;

class ComposeFieldLines
{
    private Registry $registry;

    private array $textFields = [
        Fields\Text::class,
        Fields\Textarea::class,
        Fields\Html::class,
        Fields\Repeat::class,
    ];

    private LinesCollection $lines;

    /** Avoid all field values that are not localized */
    private bool $ignoreNonLocalized = false;

    /** Avoid all field values that are not filled in the original locale */
    private bool $ignoreEmptyValues = false;

    /** Avoid all field values that belong to an offline fragment */
    private bool $ignoreOfflineFragments = false;

    /** Avoid all field values of fields with one of following field keys */
    private array $ignoredFieldKeys = [];

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        $this->lines = new LinesCollection();
    }

    public function compose(Resource $resource, $model, array $locales): static
    {
        $lines = new LinesCollection();

        $this->modelLabel = $resource->getPageTitle($model);

        $lines = $lines->merge(
            $this->extractFieldValues($resource, $model, $locales)
        );

        if($model instanceof FragmentsOwner) {
            $lines = $lines->merge($this->addFragmentFieldValues($model, $locales));
        }

        $this->addSeparator($lines);

        $this->lines = $lines;

        return $this;
    }

    public function ignoreNonLocalized(bool $ignoreNonLocaled = true): static
    {
        $this->ignoreNonLocalized = $ignoreNonLocaled;

        return $this;
    }

    public function ignoreEmptyValues(bool $ignoreEmptyValues = true): static
    {
        $this->ignoreEmptyValues = $ignoreEmptyValues;

        return $this;
    }

    public function ignoreOfflineFragments(bool $ignoreOfflineFragments = true): static
    {
        $this->ignoreOfflineFragments = $ignoreOfflineFragments;

        return $this;
    }

    public function ignoreFieldKeys(array $ignoredFieldKeys): static
    {
        $this->ignoredFieldKeys = $ignoredFieldKeys;

        return $this;
    }

    public function getLines(): LinesCollection
    {
        return $this->lines;
    }

    private function addSeparator(LinesCollection $lines): void
    {
        $lines->push(new InfoLine([]));
    }

    private function extractFieldValues($resource, $model, array $locales): LinesCollection
    {
        $lines = new LinesCollection();

        $model = $model instanceof Fragmentable ? $model->fragmentModel() : $model;

        $modelFields = Fields::makeWithoutFlatteningNestedFields($resource->fields($model))
            ->filterBy(fn ($field) => in_array($field::class, $this->textFields))
            ->model($model);

        foreach($modelFields as $field) {
            $lines = $lines->merge(
                $this->addFieldLines($resource, $model, $field, $locales)
            );
        }

        return $lines;
    }

    private function extractRepeatField(Resource $resource, $model, Fields\Repeat $field, array $locales): LinesCollection
    {
        $lines = new LinesCollection();

        if($this->ignoreEmptyValues && ! $field->getValue()) {
            return $lines;
        }

        $components = $field->getRepeatedComponents();

        foreach($components as $componentGroup) {
            foreach(Fields::make($componentGroup) as $nestedField) {
                $lines = $lines->merge(
                    $this->addFieldLines($resource, $model, $nestedField, $locales)
                );
            }
        }

        return $lines;
    }

    private function addFragmentFieldValues(FragmentsOwner $model, array $locales): LinesCollection
    {
        $lines = new LinesCollection();

        /** @var Fragmentable[] $fragment */
        $fragments = app(FragmentRepository::class)->getByOwner($model instanceof Fragmentable ? $model->fragmentModel() : $model);

        foreach($fragments as $fragment) {

            if($this->ignoreOfflineFragments && $fragment->fragmentModel()->isOffline()) {
                continue;
            }

            $lines = $lines->merge(
                $this->extractFieldValues(
                    $this->registry->resource($fragment::resourceKey()),
                    $fragment,
                    $locales
                )
            );

            // Nested fragments
            if($fragment instanceof FragmentsOwner) {
                $lines = $lines->merge(
                    $this->addFragmentFieldValues($fragment, $locales)
                );
            }
        }

        return $lines;
    }

    private function addFieldLines($resource, $model, $field, array $locales): LinesCollection
    {
        $lines = new LinesCollection();

        if(in_array($field->getKey(), $this->ignoredFieldKeys)) {
            return $lines;
        }

        if($field instanceof Fields\Repeat) {
            return $lines->merge($this->extractRepeatField($resource, $model, $field, $locales));
        }

        if($this->ignoreNonLocalized && ! $field->hasLocales()) {
            return $lines;
        }

        $values = ['x' => $field->getValue()];

        if($field->hasLocales()) {
            $values = collect($locales)->mapWithKeys(fn ($locale) => [$locale => $field->getValue($locale)]);
            $values = ! $this->ignoreNonLocalized ? ['x' => '', ...$values->all()] : $values->all();
        }

        if($this->ignoreEmptyValues && $this->areAllValuesEmpty($values)) {
            return $lines;
        }

        $fieldLabel = $field->getLabel() ?: $field->getKey();

        if(str_starts_with($field->getKey(), 'seo_')) {
            $fieldLabel = 'SEO ' . $fieldLabel;
        }

        $lines->push(new FieldLine(
            $model->modelReference()->get(),
            Fields\Common\FormKey::replaceBracketsByDots($field->getName()),
            $this->modelLabel,
            ucfirst($resource->getLabel()),
            $fieldLabel,
            $values,
        ));

        return $lines;
    }

    private function areAllValuesEmpty(array $values): bool
    {
        return collect($values)->filter(fn ($value) => ! empty($value))->isEmpty();
    }
}
