<?php

namespace Thinktomorrow\Chief\TableNew\Table\References;

use Livewire\Wireable;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\Resource;
use Thinktomorrow\Chief\Resource\TreeResource;

class ResourceReference implements Wireable
{
    private string $resourceKey;

    public function __construct(string $resourceKey)
    {
        $this->resourceKey = $resourceKey;
    }

    public function toLivewire()
    {
        return [
            'resourceKey' => $this->resourceKey,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static($value['resourceKey']);
    }

    public function getResource(): Resource
    {
        return app(Registry::class)->resource($this->resourceKey);
    }

    public function isTreeResource(): bool
    {
        return $this->getResource() instanceof TreeResource;
    }

    public function getResourceKey(): string
    {
        return $this->resourceKey;
    }
}
