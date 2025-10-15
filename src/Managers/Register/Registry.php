<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Register;

use Thinktomorrow\Chief\Managers\Exceptions\MissingResourceRegistration;
use Thinktomorrow\Chief\Managers\Exceptions\MissingTreeResource;
use Thinktomorrow\Chief\Managers\Exceptions\ResourceAlreadyRegistered;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Resource\TreeResource;

final class Registry
{
    /** @var PageResource|resource[] */
    private array $resources;

    /** @var Manager[] */
    private array $managers;

    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    public function resource(string $key): PageResource|Resource
    {
        if (! $this->exists($key)) {
            throw new MissingResourceRegistration('No resource class registered for key ['.$key.']');
        }

        return $this->resources[$key];
    }

    public function exists(string $key): bool
    {
        return isset($this->resources[$key]);
    }

    public function manager(string $key): Manager
    {
        if (! isset($this->managers[$key])) {
            throw new MissingResourceRegistration('No manager class registered for key ['.$key.']');
        }

        return $this->managers[$key];
    }

    // TODO: optimize so we can use same model for different resources (e.g. Taxon in Trader)
    // -> A second callback filter on top of the modelClass filter to distinguish between them.
    public function findResourceByModel(string $modelClass): Resource
    {
        try {
            return $this->filter(fn (Resource $resource) => $resource::modelClassName() == $modelClass)->first();
        } catch (MissingResourceRegistration $e) {
            throw new MissingResourceRegistration('No registered resource found for class ['.$modelClass.'].');
        }
    }

    public function findTreeResourceByModel(string $modelClass): TreeResource
    {
        $resource = $this->findResourceByModel($modelClass);

        if (! $resource instanceof TreeResource || ! $resource instanceof Resource) {
            throw new MissingTreeResource('Class ['.$resource::class.'] should implement '.TreeResource::class.'.');
        }

        return $resource;
    }

    public function findManagerByModel(string $modelClass): Manager
    {
        $resource = $this->findResourceByModel($modelClass);

        return $this->manager($resource::resourceKey());
    }

    /**
     * @return PageResource[]
     */
    public function pageResources(): array
    {
        return $this->filter(fn ($resource) => $resource instanceof PageResource)->all();
    }

    private function all(): array
    {
        return $this->resources;
    }

    private function first(): Resource
    {
        if (count($this->resources) < 1) {
            throw new MissingResourceRegistration('No resource found in the registry.');
        }

        return array_values($this->resources)[0];
    }

    private function filter(callable $filter): static
    {
        $resources = [];

        foreach ($this->resources as $key => $resource) {
            if ($filter($resource) == true) {
                $resources[$key] = $resource;
            }
        }

        return new self($resources);
    }

    public function registerResource(string $key, Resource $resource, Manager $manager): self
    {
        if ($this->exists($key)) {
            throw new ResourceAlreadyRegistered('Cannot register resource. The resource key ['.$key.'] is already registered.');
        }

        $this->resources[$key] = $resource;
        $this->managers[$key] = $manager;

        return $this;
    }
}
