<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestedNode;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTreeSource;
use Thinktomorrow\Vine\NodeCollectionFactory;

class MysqlNestablePageRepository implements NestablePageRepository
{
    protected ?NestedTree $tree = null;
    protected string $modelClass;

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function findNestableById(string|int $nestableId): ?NestedNode
    {
        return $this->getTree()->find(fn (NestedNode $nestable) => $nestable->getId() == $nestableId);
    }

    public function getTree(): NestedTree
    {
        if ($this->tree) {
            return $this->tree;
        }

        $this->tree = new NestedTree((new NodeCollectionFactory)
            ->fromSource(new NestedTreeSource($this->getNodes()))
            ->all());

        return $this->tree;
    }

    protected function getNodes(): array
    {
        return $this->modelClass::with(['urls', 'assetRelation','assetRelation.media'])
            ->orderBy('order')
            ->get()
            ->map(fn ($model) => new PageNode($model))
            ->all();
    }
}
