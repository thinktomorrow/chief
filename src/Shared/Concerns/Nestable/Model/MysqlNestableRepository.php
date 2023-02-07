<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestableNode;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTreeSource;
use Thinktomorrow\Vine\NodeCollectionFactory;

class MysqlNestableRepository implements NestableRepository
{
    private Registry $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    public function getTree(string $resourceKey): NestedTree
    {
        // TODO: memoize...
        $resource = $this->getResource($resourceKey);

        return $this->buildTree(
            app($resource->indexRepository(), ['resourceKey' => $resourceKey])->getNestableResults()
        );
    }

    public function buildTree(Collection $models): NestedTree
    {
        $nodes = $models->map(fn ($model) => new NestableNode($model));

        return new NestedTree((new NodeCollectionFactory)
            ->fromSource(new NestedTreeSource($nodes))
            ->all());
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
