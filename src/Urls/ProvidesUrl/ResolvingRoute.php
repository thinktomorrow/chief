<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\ProvidesUrl;

trait ResolvingRoute
{
    protected static $routeResolver;

    /**
     * Resolve a page route.
     *
     * @param $name
     * @param null $locale
     * @param array $parameters
     * @return string
     */
    protected function resolveRoute($name, $parameters = [], $locale = null)
    {
        if (static::$routeResolver) {
            return call_user_func_array(static::$routeResolver, [$name, $parameters, $locale]);
        }

        return route($name, $parameters);
    }

    public static function setRouteResolver(\Closure $resolver)
    {
        static::$routeResolver = $resolver;
    }
}
