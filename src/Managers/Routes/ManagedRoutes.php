<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Routes;

use ArrayIterator;

final class ManagedRoutes implements \IteratorAggregate, \Countable
{
    private string $managerClass;
    private string $prefix;
    private array $routes;

    private function __construct(string $managerClass, string $prefix, array $routes)
    {
        // Assert all array values are a ManagedRoute instance
        array_map(fn(ManagedRoute $route) => $route, $routes);

        $this->managerClass = $managerClass;
        $this->prefix = $prefix;
        $this->routes = $routes;
    }

    public static function empty(string $managerClass, string $prefix): self
    {
        return new static($managerClass, $prefix, []);
    }

    public function push($managedRoutes): self
    {
        return new static($this->managerClass, $this->prefix, array_merge($this->routes, (array) $managedRoutes));
    }

    public function getManagerClass(): string
    {
        return $this->managerClass;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->routes);
    }

    public function count()
    {
        return count($this->routes);
    }
}
