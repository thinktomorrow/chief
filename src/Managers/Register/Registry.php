<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Register;

use Thinktomorrow\Chief\Managers\Exceptions\MissingResourceRegistration;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\Resource;

final class Registry
{
    /** @var PageResource|Resource[] */
    private array $resources;

    /** @var Manager[] */
    private array $managers;

    public function __construct(array $resources)
    {
        $this->resources = $resources;
    }

    public function resource(string $key): PageResource|Resource
    {
        if (! isset($this->resources[$key])) {
            throw new MissingResourceRegistration('No resource class registered for key ['.$key.']');
        }

        return $this->resources[$key];
    }

    public function manager(string $key): Manager
    {
        if (! isset($this->managers[$key])) {
            throw new MissingResourceRegistration('No manager class registered for key ['.$key.']');
        }

        return $this->managers[$key];
    }

    public function findResourceByModel(string $modelClass): Resource
    {
        try {
            return $this->filter(fn (Resource $resource) => $resource::modelClassName() == $modelClass)->first();
        } catch (MissingResourceRegistration $e) {
            throw new MissingResourceRegistration('No registered resource found for ['.$modelClass.'].');
        }
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
            if (true == $filter($resource)) {
                $resources[$key] = $resource;
            }
        }

        return new static($resources);
    }

    public function registerResource(string $key, Resource $resource, Manager $manager): self
    {
        $this->resources[$key] = $resource;
        $this->managers[$key] = $manager;

        return $this;
    }
}
