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
     * How this resource is represented in the admin.
     */
    public function getLabel(): string;

    public function fields($model): iterable;
//    public function forms(): iterable;

    public function field($model, string $key): Field;
}
