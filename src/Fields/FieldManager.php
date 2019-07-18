<?php

namespace Thinktomorrow\Chief\Fields;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fields\Types\Field;

interface FieldManager
{
    /**
     * The set of fields that should be manageable for a certain model.
     *
     * Additionally, you should:
     * 1. Make sure to setup the proper migrations and
     * 2. For a translatable field you should add this field to the $translatedAttributes property of the model as well.
     *
     * @return Fields
     */
    public function fields(): Fields;

    /**
     * Triggers the save action for all prepared field values.
     *
     * @param Request $request
     * @return FieldManager
     */
    public function saveFields(Request $request);

    /**
     * Render the field view
     *
     * @param Field $field
     * @return mixed
     */
    public function renderField(Field $field);

    /**
     * The current value that this field holds. This is used to
     * populate the form fields with their default value.
     *
     * @param Field $field
     * @return mixed
     */
    public function fieldValue(Field $field);
}
