<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Visitable;

use Closure;

trait ResolvingRoute
{
    protected static $routeResolver;

    /**
     * Resolve a page route.
     */
    protected function resolveRoute(string $name, array $parameters = [], ?string $site = null)
    {
        if (static::$routeResolver) {
            return call_user_func_array(static::$routeResolver, [$name, $parameters, $site]);
        }

        return route($name, $parameters);
    }

    public static function setRouteResolver(?Closure $resolver = null)
    {
        static::$routeResolver = $resolver;
    }
}
