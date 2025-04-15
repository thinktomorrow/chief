<?php

namespace Thinktomorrow\Chief\Admin\Preferences;

class PreferredSite
{
    private static ?string $site = null;

    public static function set(string $site): void
    {
        self::$site = $site;
    }

    public static function exists(): bool
    {
        return isset(self::$site);
    }

    public static function get(): string
    {
        if (! self::exists()) {
            throw new \InvalidArgumentException('No site set.');
        }

        return self::$site;
    }

    public static function clear(): void
    {
        self::$site = null;
    }
}
