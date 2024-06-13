<?php

namespace Thinktomorrow\Chief\Plugins\TranslationsExport\Export;

use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\TranslationsExport\Document\InfoLine;
use Thinktomorrow\Chief\Plugins\TranslationsExport\Document\LinesCollection;
use Thinktomorrow\Chief\Plugins\TranslationsExport\Document\TranslationLine;
use Thinktomorrow\Chief\Resource\Resource;

class ComposeExportLines
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
    private bool $ignoreNonTranslatable = false;

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

    public function compose(Resource $resource, $model, string $locale, array $targetLocales): static
    {
        $lines = new LinesCollection();

        $this->addModelMetadata($lines, $resource, $model);

        $lines = $lines->merge(
            $this->extractFieldValues($resource, $model, $locale, $targetLocales)
        );

        if($model instanceof FragmentsOwner) {
            $lines = $lines->merge($this->addFragmentFieldValues($model, $locale, $targetLocales));
        }

        $this->addSeparator($lines);

        $this->lines = $lines;

        return $this;
    }

    public function ignoreNonTranslatable(): static
    {
        $this->ignoreNonTranslatable = true;

        return $this;
    }

    public function ignoreEmptyValues(): static
    {
        $this->ignoreEmptyValues = true;

        return $this;
    }

    public function ignoreOfflineFragments(): static
    {
        $this->ignoreOfflineFragments = true;

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

    public function getStyles(): array
    {
        // Infoline rows will be marked with a different style
        return $this->lines
            ->filter(fn ($line) => $line instanceof InfoLine)
            ->map(fn ($line) => ['fill' => ['color' => 'FFD9D9D9']])
            ->toArray();
    }

    private function addModelMetadata(LinesCollection $lines, Resource $resource, $model): void
    {
        $lines->push(new InfoLine([
            ucfirst($resource::resourceKey()) . ': ' . $resource->getPageTitle($model),
        ]));
    }

    private function addSeparator(LinesCollection $lines): void
    {
        $lines->push(new InfoLine([]));
    }

    private function extractFieldValues($resource, $model, string $locale, array $targetLocales): LinesCollection
    {
        $lines = new LinesCollection();

        $model = $model instanceof Fragmentable ? $model->fragmentModel() : $model;

        $modelFields = Fields::makeWithoutFlatteningNestedFields($resource->fields($model))
            ->filterBy(fn ($field) => in_array($field::class, $this->textFields))
            ->model($model);

        foreach($modelFields as $field) {
            $lines = $lines->merge(
                $this->addFieldLines($resource, $model, $field, $locale, $targetLocales)
            );
        }

        return $lines;
    }

    private function extractRepeatField(Resource $resource, $model, Fields\Repeat $field, string $locale, array $targetLocales): LinesCollection
    {
        $lines = new LinesCollection();

        if($this->ignoreEmptyValues && ! $field->getValue()) {
            return $lines;
        }

        $components = $field->getRepeatedComponents($locale);

        foreach($components as $componentGroup) {
            foreach(Fields::make($componentGroup) as $nestedField) {
                $lines = $lines->merge(
                    $this->addFieldLines($resource, $model, $nestedField, $locale, $targetLocales)
                );
            }
        }

        return $lines;
    }

    private function addFragmentFieldValues(FragmentsOwner $model, string $locale, array $targetLocales): LinesCollection
    {
        $lines = new LinesCollection();

        /** @var Fragmentable[] $fragment */
        $fragments = app(FragmentRepository::class)->getByOwner($model instanceof Fragmentable ? $model->fragmentModel() : $model);

        foreach($fragments as $fragment) {

            if($this->ignoreOfflineFragments && $fragment->fragmentModel()->isOffline()) {
                continue;
            }

            $fragmentResource = $this->registry->resource($fragment::resourceKey());

            $lines = $lines->merge(
                $this->extractFieldValues($fragmentResource, $fragment, $locale, $targetLocales)
            );

            // Nested fragments ...
            if($fragment instanceof FragmentsOwner) {
                $lines = $lines->merge(
                    $this->addFragmentFieldValues($fragment, $locale, $targetLocales)
                );
            }
        }

        return $lines;
    }

    private function addFieldLines($resource, $model, $field, string $locale, array $targetLocales): LinesCollection
    {
        $lines = new LinesCollection();

        if(in_array($field->getKey(), $this->ignoredFieldKeys)) {
            return $lines;
        }

        if($field instanceof Fields\Repeat) {
            return $lines->merge($this->extractRepeatField($resource, $model, $field, $locale, $targetLocales));
        }

        if($this->ignoreNonTranslatable && ! $field->hasLocales()) {
            return $lines;
        }

        if($this->ignoreEmptyValues && ! $field->getValue($locale)) {
            return $lines;
        }

        $fieldLabel = $field->getLabel() ?: $field->getKey();

        if(str_starts_with($field->getKey(), 'seo_')) {
            $fieldLabel = 'SEO ' . $fieldLabel;
        }

        $lines->push(new TranslationLine(
            $model->modelReference()->get(),
            $field->getKey(),
            ucfirst($resource->getLabel()),
            $fieldLabel,
            $field->getValue($locale),
            collect($targetLocales)->mapWithKeys(fn ($targetLocale) => [$targetLocale => $field->getValue($targetLocale)])->all(),
        ));

        return $lines;
    }
}
