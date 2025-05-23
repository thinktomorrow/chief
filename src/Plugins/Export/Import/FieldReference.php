<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\FieldName\FieldNameHelpers;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Fragments\App\Repositories\FragmentFactory;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Export\Export\Lines\FieldLine;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FieldReference
{
    private Resource $resource;

    private Model $model;

    private Field $field;

    private string $fieldName;

    public function __construct(Resource $resource, Model $model, Field $field, string $fieldName)
    {
        $this->resource = $resource;
        $this->model = $model;
        $this->field = $field;
        $this->fieldName = $fieldName;
    }

    public static function fromEncryptedKey($encryptedKey): static
    {
        $decryptedKey = decrypt($encryptedKey);

        [$modelReference, $fieldName] = explode('|', $decryptedKey);

        $model = ModelReference::fromString($modelReference)->instance();

        if ($model instanceof FragmentModel) {
            $resource = app(FragmentFactory::class)->create($model);
        } else {
            $resource = app(Registry::class)->findResourceByModel($model::class);
        }

        // Extract the field key and find the field by the dotted field name
        $fieldKey = strpos($fieldName, '.') !== false ? substr($fieldName, 0, strpos($fieldName, '.')) : $fieldName;
        $field = $resource->field($model, $fieldKey);

        return new static($resource, $model, $field, $fieldName);
    }

    public function getValue(string $locale)
    {
        return $this->field->getValue($locale);
    }

    public function saveValue($value, string $locale)
    {
        $fields = Fields::make([$this->field]);

        $this->field->name($this->fieldName); // To support nested fields
        $key = FieldNameHelpers::replaceBracketsByDots(
            $locale !== FieldLine::NON_LOCALIZED
                ? $this->field->getName($locale)
                : $this->field->getName()
        );

        $payload = Arr::undot([$key => $value]);

        // Hack way to save repeat fields
        if ($this->field instanceof Repeat && $this->field->hasLocales()) {

            // Merge current values with passed value
            $nestedFieldKey = substr($this->fieldName, strpos($this->fieldName, '.') + 1);

            $nestedFieldKey = $locale.'.'.$nestedFieldKey;

            $payload = Arr::undot([$nestedFieldKey => $value]);
            $payload = $this->array_merge_overwrite($this->field->getAllValues(), $payload);

            $this->model->{$this->field->getColumnName()} = $payload;

            $this->model->save();

            return;
        }

        // app(Fields\Validation\FieldValidator::class)->handle($fields, $payload);

        app($this->resource->getSaveFieldsClass())->save(
            $this->model,
            $fields,
            $payload,
            []
        );
    }

    public function isLocalized(): bool
    {
        return $this->field->hasLocales();
    }

    public function isRepeatField(): bool
    {
        return $this->field instanceof Repeat;
    }

    private function array_merge_overwrite(): array
    {
        $arrays = func_get_args();
        $result = [];

        foreach ($arrays as $array) {

            if (! $array) {
                continue;
            }

            foreach ($array as $key => $value) {
                if (is_array($value) && isset($result[$key]) && is_array($result[$key])) {
                    $result[$key] = $this->array_merge_overwrite($result[$key], $value);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}
