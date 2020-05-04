<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\AbstractField;
use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Fields\Types\FieldType;
use Thinktomorrow\Chief\Fields\Types\MediaField;

class FragmentField extends AbstractField implements Field
{
    /** @var Fields */
    private $fields;

    /** @var null|string */
    private $fragmentLabel;

    public static function make(string $key, Fields $fields): Field
    {
        return (new static(new FieldType(FieldType::FRAGMENT), $key))
            ->fields($fields);
    }

    private function fields(Fields $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    public function getFields(): Fields
    {
        return $this->fields;
    }

    /**
     * @param string|null $locale
     * @return Fields[]
     */
    public function getFragments(?string $locale = null): array
    {
        // By default there is one empty Fragment provided to the user
        $fragments = [Fragment::empty($this->getKey())];

        // Model is auto-injected by Manager::editFields() method.
        if (($this->model)) {
            if (!method_exists($this->model, 'getFragments')) {
                throw new \RuntimeException(get_class($this->model) . ' is missing the ' . HasFragments::class . ' trait.');
            }

            if (count($modelFragments = $this->model->getFragments($this->getKey())) > 0) {
                $fragments = $modelFragments->map(function (FragmentModel $fragmentModel) {
                    return Fragment::fromModel($fragmentModel);
                })->all();
            }
        }

        foreach ($fragments as $k => $fragment) {
            $fragments[$k] = $fragments[$k]
                ->setModelIdInputName($this->name . '[' . $k . '][modelId]')
                ->setFields($this->fields->clone()->map(function (Field $field) use ($k, $fragment) {
                    return $field->name($this->name . '.' . $k . '.' . $field->getName())
                                 ->localizedFormat(':name.:locale')
                                 ->valueResolver(function ($model = null, $locale = null, $field) use ($fragment) {
                                     if (isset($field->value)) {
                                         return $field->value;
                                     }

                                     if ($field instanceof MediaField) {
                                         if (!$fragment->hasModelId()) {
                                             return [];
                                         }

                                         return $field->getMedia(FragmentModel::find($fragment->getModelId()), $locale);
                                     }

                                     return $fragment->getValue($field->getColumn(), $locale);
                                 });
                }));
        }

        return $fragments;
    }

    /**
     * A fragmentlabel is shown in admin as the label above each fragment.
     *
     * @param string $fragmentLabel
     * @return $this
     */
    public function fragmentLabel(string $fragmentLabel): self
    {
        $this->fragmentLabel = $fragmentLabel;

        return $this;
    }

    public function getFragmentLabel(): string
    {
        return $this->fragmentLabel ?? 'fragment';
    }

    public function getDuplicatableFields(): array
    {
        if (count($this->getFragments()) < 1) {
            return [];
        }

        // Take the fields from the first fragment as a starting point for duplication
        return array_map(function (\Thinktomorrow\Chief\Fields\Types\Field $field) {
            return $field->valueResolver(function ($model = null, $locale = null, $field) {
                if ($field instanceof \Thinktomorrow\Chief\Fields\Types\MediaField) {
                    return [];
                }
                return null;
            })->render();
        }, $this->getFragments()[0]->getFields()->clone()->all());
    }
}
