<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fields;

use Illuminate\Http\Request;

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

    public function editFields(): Fields;

    public function createFields(): Fields;

    /**
     * Triggers the create save action for all prepared field values.
     *
     * @param Request $request
     */
    public function saveCreateFields(Request $request): void;

    /**
     * Triggers the edit save action for all prepared field values.
     *
     * @param Request $request
     */
    public function saveEditFields(Request $request): void;
}
