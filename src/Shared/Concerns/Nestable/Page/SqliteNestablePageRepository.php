<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Page;

use Illuminate\Support\Facades\DB;

class SqliteNestablePageRepository extends MysqlNestablePageRepository implements NestablePageRepository
{
    protected function getNodes(): array
    {
        $modelClassInstance = (new $this->modelClass);
        $table = $modelClassInstance->getTable();

        $results = DB::table($table)
            ->leftJoin('chief_urls', function ($join) use ($table, $modelClassInstance) {
                $join->on($table . '.'. $modelClassInstance->getKeyName(), 'chief_urls.model_id')
                    ->where('chief_urls.model_type', $modelClassInstance->getMorphClass());
            })
            ->select($table .'.*', DB::raw('"[" || GROUP_CONCAT(JSON_OBJECT(IFNULL(chief_urls.locale, ""), IFNULL(chief_urls.slug, ""))) || "]" AS slugs'))
            ->groupBy($table . '.'. $modelClassInstance->getKeyName())
            ->orderBy($table.'.order')
            ->get();

        return $results->map(fn ($row) => new PageNode(new $this->modelClass((array) $row)))->all();
    }
}
