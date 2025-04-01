<?php

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

trait WithCustomFieldDefinitions
{
    private static ?\Closure $fieldsDefinition = null;

    public function fields($model): iterable
    {
        if (! static::$fieldsDefinition) {
            return $this->defaultFields($model);
        }

        return call_user_func_array(static::$fieldsDefinition, [$model]);
    }

    public static function setFieldsDefinition(callable $fields): void
    {
        static::$fieldsDefinition = $fields;
    }

    public static function resetFieldsDefinition(): void
    {
        static::$fieldsDefinition = null;
    }

    private function defaultFields($model): iterable
    {
        return [];
    }
}
