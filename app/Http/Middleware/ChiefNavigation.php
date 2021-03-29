<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\Container;
use Thinktomorrow\Chief\Admin\Nav\Nav;
use Thinktomorrow\Chief\Admin\Nav\NavItem;
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
        foreach ($this->registry->managersWithTags() as $managerWithTags) {
            if ($managerWithTags->manager->can('index')) {
                $modelClass = $managerWithTags->manager->managedModelClass();
                $navLabel = (new $modelClass)->adminConfig()->getNavTitle();

                $this->container->make(Nav::class)->add(new NavItem($navLabel,                    $managerWithTags->manager->route('index'),                    $managerWithTags->tags));
            }
        }

        return $next($request);
    }
}
