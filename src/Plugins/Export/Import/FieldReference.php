<?php

namespace Thinktomorrow\Chief\Plugins\Export\Import;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class FieldReference
{
    private Resource $resource;
    private Model $model;
    private Field $field;

    private function __construct(Resource $resource, Model $model, Field $field)
    {
        $this->resource = $resource;
        $this->model = $model;
        $this->field = $field;
    }

    public static function fromEncryptedKey($encryptedKey): static
    {
        $decryptedKey = decrypt($encryptedKey);

        [$modelReference, $fieldKey] = explode('|', $decryptedKey);

        $model = ModelReference::fromString($modelReference)->instance();

        if($model instanceof FragmentModel) {
            $resourceKey = ModelReference::fromString($model->model_reference)->shortClassName();
            $resource = app(Registry::class)->resource($resourceKey);
        } else {
            $resource = app(Registry::class)->findResourceByModel($model::class);
        }

        return new static($resource, $model, $resource->field($model, $fieldKey));
    }

    public function getValue(string $locale)
    {
        return $this->field->getValue($locale);
    }

    public function saveValue($value, string $locale)
    {
        $fields = Fields::make([$this->field]);

        $key = Fields\Common\FormKey::replaceBracketsByDots($this->field->getName($locale));
        $payload = Arr::undot([$key => $value]);

        // app(Fields\Validation\FieldValidator::class)->handle($fields, $payload);
        app($this->resource->getSaveFieldsClass())->save(
            $this->model,
            $fields,
            $payload,
            []
        );
    }
}
