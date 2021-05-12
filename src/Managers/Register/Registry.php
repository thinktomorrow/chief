<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Managers\Register;

use Thinktomorrow\Chief\Managers\Exceptions\MissingManagerRegistration;
use Thinktomorrow\Chief\Managers\Exceptions\MissingModelRegistration;
use Thinktomorrow\Chief\Managers\Manager;

final class Registry
{
    private array $models;
    private array $managers;
    private TaggedKeys $tags;

    public function __construct(array $models, array $managers, TaggedKeys $tags)
    {
        $this->models = $models;
        $this->managers = $managers;
        $this->tags = $tags; // TODO: make a weakmap when in PHP 8.0 because it can be deleted if registry is deleted.
    }

    public function modelClass(string $key): string
    {
        if (! isset($this->models[$key])) {
            throw new MissingModelRegistration('No model class registered for key ['.$key.']');
        }

        return $this->models[$key];
    }

    public function manager(string $key): Manager
    {
        if (! isset($this->managers[$key])) {
            throw new MissingManagerRegistration('No manager registered for key ['.$key.']');
        }

        return $this->managers[$key];
    }

    public function managers(): array
    {
        return $this->managers;
    }

    public function models(): array
    {
        return $this->models;
    }

    public function managersWithTags(): array
    {
        return collect($this->tags->get())->reject(function ($_tags, $key) {
            return ! isset($this->managers[$key]);
        })->map(function ($tags, $key) {
            return (object)[
                'manager' => $this->manager($key),
                'tags' => $tags,
            ];
        })->toArray();
    }

    public function tagged(string $tag): self
    {
        $tags = $this->tags->tagged($tag);
        $taggedKeys = $tags->getKeys();

        $filteredModels = array_filter($this->models, function ($key) use ($taggedKeys) {
            return in_array($key, $taggedKeys);
        }, ARRAY_FILTER_USE_KEY);

        $filteredManagers = array_filter($this->managers, function ($key) use ($taggedKeys) {
            return in_array($key, $taggedKeys);
        }, ARRAY_FILTER_USE_KEY);

        return new static($filteredModels, $filteredManagers, $tags);
    }

    public function registerModel(string $key, string $modelClass): self
    {
        $this->models[$key] = $modelClass;

        return $this;
    }

    public function registerManager(string $key, Manager $manager): self
    {
        $this->managers[$key] = $manager;

        return $this;
    }

    public function registerTags(string $key, array $tags): self
    {
        $this->tags->tag($key, $tags);

        return $this;
    }
}
