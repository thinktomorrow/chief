<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Routes;

use Illuminate\Routing\Router;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Managers\Manager;

final class RegisterManagedRoutes
{
    private string $routePrefix;

    /** @var string[] */
    private array $routeMiddleware;

    /** @var Router */
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->routePrefix = config('chief.route.prefix', 'admin');
        $this->routeMiddleware = ['web-chief', 'auth:chief'];
    }

    public function __invoke(Manager $manager, ManagedRoutes $managedRoutes, string $controllerClass): void
    {
        $managedRoutes = $this->expandWithAssistantRoutes($manager, $managedRoutes);

        $this->router->group(['prefix' => $this->routePrefix, 'middleware' => $this->routeMiddleware], function () use ($managedRoutes, $controllerClass) {
            foreach ($managedRoutes as $route) {
                $this->router->addRoute($route->method,                    $managedRoutes->getPrefix() .'/'. $route->uri,                    [$controllerClass, Str::camel($route->action)])->name('chief.' . $managedRoutes->getPrefix() . '.' . $route->action);
            }
        });
    }

    private function expandWithAssistantRoutes(Manager $manager, ManagedRoutes $managedRoutes): ManagedRoutes
    {
        foreach (class_uses_recursive($manager) as $trait) {
            $method = 'routes'.class_basename($trait);
            if (public_method_exists($manager, $method)) {
                $managedRoutes = $managedRoutes->push($manager->$method());
            }
        }

        if (public_method_exists($manager, 'routes')) {
            $managedRoutes = $managedRoutes->push($manager->routes());
        }

        return $managedRoutes;
    }
}
