<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

class MysqlNestableRepository implements NestableRepository
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function getTree(string $resourceKey): NestedTree
    {
        $resource = $this->getResource($resourceKey);

        return $this->buildTree(
            app($resource->indexRepository(), ['resourceKey' => $resourceKey])->getNestableResults()
        );
    }

    public function getTreeIds(string $resourceKey): NestedTree
    {
        $resource = $this->getResource($resourceKey);

        // TODO: simplify this to come from resource instead...
        return $this->buildTree(
            app($resource->indexRepository(), ['resourceKey' => $resourceKey])->getNestableResultsAsIds()
        );
    }

    public function buildTree(Collection $models): NestedTree
    {
        return NestedTree::fromIterable($models);
    }

    public function getResource(string $resourceKey): PageResource
    {
        $resource = $this->registry->resource($resourceKey);

        if (! $resource instanceof PageResource) {
            throw new \DomainException('Resource [' . $resource::class . '] is expected to be a ' . PageResource::class . ', but it is not.');
        }

        return $resource;
    }
}
