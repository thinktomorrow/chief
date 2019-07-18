<?php

namespace Thinktomorrow\Chief\Fields;

use Thinktomorrow\Chief\Fields\Types\Field;

trait RenderingFields
{
    public function renderField(Field $field)
    {
        return view($field->view(), array_merge([
            'field'           => $field,
            'key'             => $field->key(), // As parameter so that it can be altered for translatable values
            'manager'         => $this,
            'formElementView' => $field->formElementView(),
        ]), $field->viewData())->render();
    }

    public function fieldValue(Field $field, $locale = null)
    {
        return $field->getFieldValue($this->model, $locale);
    }
}
