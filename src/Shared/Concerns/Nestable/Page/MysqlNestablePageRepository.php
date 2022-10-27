<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Illuminate\Support\Facades\DB;
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
            ->strict()
            ->fromSource(new NestedTreeSource($this->getNodes()))
            ->all());

        return $this->tree;
    }

    protected function getNodes(): array
    {
        // TODO: use eloquent models!!!!!!!! and eager loading
        // TODO: use security stuff

        $modelClassInstance = (new $this->modelClass);
        $table = $modelClassInstance->getTable();

        $results = DB::table($table)
            ->leftJoin('chief_urls', function ($join) use ($table, $modelClassInstance) {
                $join->on($table . '.'. $modelClassInstance->getKeyName(), 'chief_urls.model_id')
                    ->where('chief_urls.model_type', $modelClassInstance->getMorphClass());
            })
            ->select($table .'.*', DB::raw('CONCAT("[",GROUP_CONCAT(JSON_OBJECT(chief_urls.locale, chief_urls.slug)),"]") AS slugs'))
            ->groupBy($table . '.'. $modelClassInstance->getKeyName())
            ->orderBy($table.'.order')
            ->get();

        return $results->map(fn ($row) => new PageNode(new $this->modelClass((array) $row)))->all();
    }
}
