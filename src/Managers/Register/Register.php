<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Register;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Request\ManagerRequestDispatcher;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoutes;
use Thinktomorrow\Chief\Managers\Routes\RegisterManagedRoutes;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Shared\AdminEnvironment;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

final class Register
{
    private Container $container;
    private AdminEnvironment $adminEnvironment;

    public function __construct(Container $container, AdminEnvironment $adminEnvironment)
    {
        $this->container = $container;
        $this->adminEnvironment = $adminEnvironment;
    }

    public function fragment(string $fragmentClass): void
    {
        $this->resource($fragmentClass, FragmentManager::class);
    }

    public function resource(string $resourceClass, string $managerClass = PageManager::class): void
    {
        $this->register(
            $resource = $this->container->makeWith($resourceClass),
            $this->container->makeWith($managerClass, ['resource' => $resource])
        );
    }

    private function register(Resource $resource, Manager $manager): void
    {
        $this->assertModelIsReferable($resource::modelClassName());

        $this->registerMorphMap($resource::resourceKey(), $resource::modelClassName());

        $resource->setManager($manager);

        // Add to chief registry
        $this->container->make(Registry::class)
            ->registerResource($resource::resourceKey(), $resource, $manager)
        ;

        // Register routes only when in admin...
        if (! $this->adminEnvironment->check()) {
            return;
        }

        $this->container->make(RegisterManagedRoutes::class)(
            $manager,
            ManagedRoutes::empty($manager::class, $resource::resourceKey()),
            ManagerRequestDispatcher::class,
        );
    }

    // Add to eloquent db morph map
    private function registerMorphMap(string $key, string $modelClass)
    {
        Relation::morphMap([
            $key => $modelClass,
        ]);
    }

    private function assertModelIsReferable(string $modelClass): void
    {
        if (! (new \ReflectionClass($modelClass))->implementsInterface(ReferableModel::class)) {
            throw new \InvalidArgumentException($modelClass.' should implement '.ReferableModel::class);
        }
    }
}
