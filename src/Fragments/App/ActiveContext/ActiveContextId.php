<?php

namespace Thinktomorrow\Chief\Fragments\App\ActiveContext;

class ActiveContextId
{
    private static ?string $activeContextId = null;

    public static function set(null|string|int $activeContextId): void
    {
        self::$activeContextId = $activeContextId ? (string) $activeContextId : null;
    }

    public static function exists(): bool
    {
        return isset(self::$activeContextId);
    }

    public static function get(): string
    {
        if (! self::exists()) {
            throw new \InvalidArgumentException('No active context id set.');
        }

        return self::$activeContextId;
    }
}
