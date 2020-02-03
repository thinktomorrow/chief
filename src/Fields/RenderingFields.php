<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields;

use Thinktomorrow\Chief\Fields\Types\Field;

trait RenderingFields
{
    public function renderField(Field $field)
    {
        return view($field->getView(), array_merge([
            'field'           => $field,
            'key'             => $field->getKey(), // As parameter so that it can be altered for translatable values
            'manager'         => $this,
            'formElementView' => $field->getElementView(),
        ]), $field->getViewData())->render();
    }

    public function fieldValue(Field $field, $locale = null)
    {
        return $field->getValue($this->model, $locale);
    }
}
