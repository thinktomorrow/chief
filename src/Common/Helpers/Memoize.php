<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Common\Helpers;

use Illuminate\Database\Eloquent\Model;

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
        $cachableParameters = $this->convertToCachableParameters($parameters);

        $cachekey = $this->baseKey . ':' . md5(implode('', $cachableParameters));

        if (isset(static::$cache[$cachekey])) {
            return static::$cache[$cachekey];
        }

        return static::$cache[$cachekey] = call_user_func_array($closure, $parameters);
    }

    public static function clear()
    {
        static::$cache = [];
    }

    private function convertToCachableParameters(array $parameters)
    {
        foreach ($parameters as $key => $value) {
            if ($value instanceof Model) {
                $parameters[$key] = get_class($value) . '@' . $value->id;
            }
        }

        return $parameters;
    }
}
