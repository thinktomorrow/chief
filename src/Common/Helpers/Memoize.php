<?php

namespace Thinktomorrow\Chief\Common\Helpers;

class Memoize
{
    public static $cache = [];

    private $baseKey;

    public function __construct($baseKey)
    {
        $this->baseKey = $baseKey;
    }

    public function run(\Closure $closure, array $parameters = [])
    {
        // construct cachekey
        $cachekey = $this->baseKey.':'.md5(implode('', $parameters));

        if (isset(static::$cache[$cachekey])) {
            return static::$cache[$cachekey];
        }

        return static::$cache[$cachekey] = call_user_func_array($closure, $parameters);
    }

    public static function clear()
    {
        static::$cache = [];
    }
}
