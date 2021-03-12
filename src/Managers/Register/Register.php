<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Register;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Fragments\StaticFragmentManager;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Presets\PageManager;
use Thinktomorrow\Chief\Managers\Request\ManagerRequestDispatcher;
use Thinktomorrow\Chief\Managers\Routes\ManagedRoutes;
use Thinktomorrow\Chief\Managers\Routes\RegisterManagedRoutes;

final class Register
{
    /** @var Container */
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function staticFragment(string $fragmentClass, $tags = []): void
    {
        $this->register($fragmentClass,            $this->container->makeWith(StaticFragmentManager::class, ['managedModelClass' => $fragmentClass]),            $tags, );
    }

    public function model(string $modelClass, string $managerClass = PageManager::class, $tags = ['nav']): void
    {
        $this->register($modelClass,            $this->container->makeWith($managerClass, ['managedModelClass' => $modelClass]),            $tags);
    }

    private function register(string $modelClass, Manager $manager, $tags = []): void
    {
        // Check if model class points to ManagedModel interface
        $ref = new \ReflectionClass($modelClass);
        if (! $ref->implementsInterface(ManagedModel::class)) {
            throw new \DomainException('Class ' . $modelClass . ' should implement contract ' . ManagedModel::class);
        }

        $managedModelKey = $modelClass::managedModelKey();

        // Add to chief registry
        $this->container->make(Registry::class)
            ->registerModel($managedModelKey, $modelClass)
            ->registerManager($managedModelKey, $manager)
            ->registerTags($managedModelKey, (array) $tags);

        // Register routes
        $this->container->make(RegisterManagedRoutes::class)($manager,            ManagedRoutes::empty(get_class($manager), $managedModelKey),            ManagerRequestDispatcher::class, );

        // Add to eloquent db morph map
        Relation::morphMap([
            $managedModelKey => $modelClass,
        ]);
    }
}
