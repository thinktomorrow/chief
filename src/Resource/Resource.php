<?php

namespace Thinktomorrow\Chief\Resource;

use Thinktomorrow\Chief\Forms\Fields\Field;

interface Resource
{
    /**
     * Unique identifier in the chief application.
     */
    public static function resourceKey(): string;

    public static function modelClassName(): string;

    /**
     * The label that represents this resource in the admin.
     */
    public function getLabel(): string;

    public function fields($model): iterable;

    public function field($model, string $key): Field;

    /**
     * Retrieve a nested field (from RepeatField)
     */
    public function nestedField($model, string $key): Field;

    public function getSaveFieldsClass(): string;

    /** The attributes on a new model object - used on create */
    public function getAttributesOnCreate(array $input = []): array;

    /**
     * Adjust the create input before saving it as a new record.
     */
    public function prepareInputOnCreate(array $input): array;
}
