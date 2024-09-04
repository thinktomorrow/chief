<?php

namespace Thinktomorrow\Chief\Assets\App\ExternalFiles;

class DriverFactory
{
    public static array $map = [];

    public function create(string $driverType): Driver
    {
        foreach (static::$map as $type => $class) {
            if ($type == $driverType) {
                return app($class);
            }
        }

        throw new \InvalidArgumentException('No driver found for type ' . $driverType);
    }

    /**
     * The string representation of the external driver should correspond with
     * the assetlibrary.types key. The latter points to the specific Asset class.
     */
    public static function addDriver(string $driverType, string $driverClass): void
    {
        static::$map[$driverType] = $driverClass;
    }

    public static function any(): bool
    {
        return count(static::$map) > 0;
    }
}
