<?php

namespace Thinktomorrow\Chief\Tests\Feature\Assistants;

/**
 * convenience class to check if a method from the assistant (above) has been reached / called
 */
class AssistantSaveMethodVerification{

    public static $value = null;

    public static function set($value)
    {
        static::$value = $value;
    }

    public static function unset()
    {
        static::$value = null;
    }
}
