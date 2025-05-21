<?php

namespace Thinktomorrow\Chief\Plugins\Export\Export\Lines;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldNameHelpers;
use Thinktomorrow\Chief\Forms\Fields\Html;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;

class ComposeFieldLines
{
    private Registry $registry;

    private array $textFields = [
        Text::class,
        Textarea::class,
        Html::class,
        Repeat::class,
    ];

    private LinesCollection $lines;

    /** Avoid all field values that are not localized */
    private bool $ignoreNonLocalized = false;

    /** Avoid all field values that are not filled in the original locale */
    private bool $ignoreEmptyValues = false;

    /** Avoid all field values that belong to an offline fragment */
    private bool $ignoreOfflineFragments = false;

    /** Only show shared fragment values the first time they appear */
    private array $ignoredFragments = [];

    /** Avoid all field values of fields with one of following field keys */
    private array $ignoredFieldKeys = [];

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
        $this->lines = new LinesCollection;
    }

    public function compose(Resource $resource, $model, array $locales): static
    {
        $lines = new LinesCollection;

        $this->modelLabel = $resource->getPageTitle($model);

        $lines = $lines->merge(
            $this->extractFieldValues($resource, $model, $locales)
        );

        if ($model instanceof ContextOwner) {
            $lines = $lines->merge($this->addFragmentFieldValues($model, $locales));
        }

        $this->lines = $lines;

        return $this;
    }

    public function ignoreFragments(array $ignoredFragments = []): static
    {
        $this->ignoredFragments = $ignoredFragments;

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

    private function extractFieldValues(Resource $resource, $model, array $locales): LinesCollection
    {
        $lines = new LinesCollection;

        $model = $model instanceof Fragment ? $model->getFragmentModel() : $model;

        $modelFields = Fields::makeWithoutFlatteningNestedFields($resource->fields($model))
            ->filterBy(fn ($field) => in_array($field::class, $this->textFields))
            ->model($model);

        foreach ($modelFields as $field) {
            $lines = $lines->merge(
                $this->addFieldLines($resource, $model, $field, $locales)
            );
        }

        return $lines;
    }

    //    private function extractRepeatField(Resource $resource, $model, Repeat $field, array $locales): LinesCollection
    //    {
    //        // Repeat field itself is localized... Items not.
    //        // TODO: Protect in repeat field itself by checking if items has locales
    //
    //        $lines = new LinesCollection;
    //
    //        if ($this->ignoreEmptyValues && ! $field->getValue()) {
    //            return $lines;
    //        }
    //
    //        $components = $field->getRepeatedComponents();
    //        foreach ($components as $componentGroup) {
    //            foreach (Fields::make($componentGroup) as $nestedField) {
    //                $lines = $lines->merge(
    //                    $this->addFieldLines($resource, $model, $nestedField, $locales)
    //                );
    //            }
    //        }
    //
    //        return $lines;
    //    }
    //
    //    private function flattenRepeatFieldValues(Repeat $field, array $values): array
    //    {
    //        $flattened = [];
    //
    //        foreach ($values as $key => $value) {
    //            if (is_array($value)) {
    //                $flattened = array_merge($flattened, $this->flattenRepeatFieldValues($field, $value));
    //            } else {
    //                $flattened[$field->getKey().'.'.$key] = $value;
    //            }
    //        }
    //
    //        return $flattened;
    //    }

    private function addFragmentFieldValues(ContextOwner $model, array $locales): LinesCollection
    {
        $lines = new LinesCollection;

        $contexts = app(ContextRepository::class)->getByOwner($model->modelReference());

        foreach ($contexts as $context) {
            /** @var Fragment[] $fragment */
            $fragmentCollection = app(FragmentRepository::class)->getFragmentCollection($context->id);
            $fragments = $fragmentCollection->flatten();

            foreach ($fragments as $fragment) {

                if ($this->ignoreOfflineFragments && $fragment->getFragmentModel()->isOffline()) {
                    continue;
                }

                // Shared fragments are only exported once to reduce translation lines
                // First time we encounter a shared fragment, we add it to the ignored list
                if ($fragment->getFragmentModel()->isShared()) {
                    if (in_array($fragment->getFragmentModel()->id, $this->ignoredFragments)) {
                        continue;
                    } else {
                        $this->ignoredFragments[] = $fragment->getFragmentModel()->id;
                    }
                }

                $lines = $lines->merge(
                    $this->extractFieldValues(
                        $fragment, // Fragment is resource
                        $fragment,
                        $locales
                    )
                );
            }
        }

        return $lines;
    }

    private function addFieldLines(Resource $resource, $model, $field, array $locales): LinesCollection
    {
        $lines = new LinesCollection;

        if (in_array($field->getKey(), $this->ignoredFieldKeys)) {
            return $lines;
        }

        if ($field instanceof Repeat) {

            if ($field->hasLocales()) {
                $values = collect($locales)->mapWithKeys(fn ($locale) => [$locale => $field->getValue($locale)]);
            } else {
                $values = [FieldLine::NON_LOCALIZED => $field->getValue()];
            }

            $valuesGroups = $this->extractRepeatValues($values);

            foreach ($valuesGroups as $index => $valuesGroup) {

                foreach ($valuesGroup as $key => $_values) {

                    $_values = ! $this->ignoreNonLocalized ? [FieldLine::NON_LOCALIZED => '', ...$_values] : $_values;

                    $lines->push(new FieldLine(
                        $model->modelReference()->get(),
                        FieldNameHelpers::replaceBracketsByDots($field->getName()).'.'.$index.'.'.$key,
                        $this->modelLabel,
                        ucfirst($resource->getLabel()),
                        ($field->getLabel() ?: $field->getKey()).' > '.$key,
                        $_values,
                    ));
                }
            }

            return $lines;
        }

        if ($this->ignoreNonLocalized && ! $field->hasLocales()) {
            return $lines;
        }

        $values = [FieldLine::NON_LOCALIZED => $field->getValue()];

        if ($field->hasLocales()) {
            $values = collect($locales)->mapWithKeys(fn ($locale) => [$locale => $field->getValue($locale)]);
            $values = ! $this->ignoreNonLocalized ? [FieldLine::NON_LOCALIZED => '', ...$values->all()] : $values->all();
        }

        if ($this->ignoreEmptyValues && $this->areAllValuesEmpty($values)) {
            return $lines;
        }

        $fieldLabel = $field->getLabel() ?: $field->getKey();

        if (str_starts_with($field->getKey(), 'seo_')) {
            $fieldLabel = 'SEO '.$fieldLabel;
        }

        $lines->push(new FieldLine(
            $model->modelReference()->get(),
            FieldNameHelpers::replaceBracketsByDots($field->getName()),
            $this->modelLabel,
            ucfirst($resource->getLabel()),
            $fieldLabel,
            $values,
        ));

        return $lines;
    }

    private function extractRepeatValues(Collection|array $input): array
    {
        if (! is_array($input)) {
            $input = $input->all();
        }

        $locales = array_keys($input);

        $maxCount = max(array_map(fn ($_input) => ! $_input || ! is_array($_input) ? 0 : count($_input), $input));

        // Collect all field names used anywhere
        $fieldKeys = [];
        foreach ($input as $localeEntries) {

            // null value, so nothing exists yet for this repeat locale.
            if (! is_array($localeEntries)) {
                continue;
            }

            foreach ($localeEntries as $entry) {
                foreach ($entry as $field => $_) {
                    $fieldKeys[$field] = true;
                }
            }
        }
        $fieldKeys = array_keys($fieldKeys);

        $result = [];

        for ($i = 0; $i < $maxCount; $i++) {
            $item = [];

            foreach ($fieldKeys as $fieldKey) {
                $item[$fieldKey] = [];

                foreach ($locales as $locale) {
                    $value = $input[$locale][$i][$fieldKey] ?? null;
                    $item[$fieldKey][$locale] = $value;
                }
            }

            $result[] = $item;
        }

        return $result;
    }

    private function areAllValuesEmpty(array $values): bool
    {
        return collect($values)->filter(fn ($value) => ! empty($value))->isEmpty();
    }

    public function getIgnoredSharedFragments(): array
    {
        return $this->ignoredFragments;
    }
}
