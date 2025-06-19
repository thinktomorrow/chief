<?php

namespace Thinktomorrow\Chief\Plugins\Hive\Drivers;

class DriverFactory
{
    public static function create(string $driver): Driver
    {
        $driverClass = __NAMESPACE__.'\\'.ucfirst($driver).'Driver';

        if (! class_exists($driverClass)) {
            throw new \InvalidArgumentException("Driver class {$driverClass} does not exist.");
        }

        return app()->make($driverClass);
    }
}
