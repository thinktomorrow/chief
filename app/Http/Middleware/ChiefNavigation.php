<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;
use Thinktomorrow\Chief\Admin\Nav\Nav;
use Thinktomorrow\Chief\Managers\Register\Registry;

final class ChiefNavigation
{
    /** @var Container */
    private $container;

    /** @var Registry */
    private Registry $registry;

    public function __construct(Registry $registry, Container $container)
    {
        $this->container = $container;
        $this->registry = $registry;
    }

    public function handle($request, Closure $next)
    {
        foreach ($this->registry->pageResources() as $resource) {
            if ($navItem = $resource->getNavItem()) {
                $this->container->make(Nav::class)->add($navItem);
            }
        }

        return $next($request);
    }
}
