<?php

namespace Thinktomorrow\Chief\Table\Filters\Presets;

use Thinktomorrow\Chief\Table\Filters\SearchFilter;

class TitleFilter extends SearchFilter
{
    public static function makeDefault(array $columns = [], array $dynamicKeys = ['title'], string $dynamicColumn = 'values'): self
    {
        // $keys, $input, $column = 'values', $table = null, bool $split_by_spaces = true, bool $orClause = false
        $object = static::make('title')
            ->label('Titel')
            ->placeholder('Zoek op titel')
            ->description('Zoek op pagina titel')
            ->query(FilterPresets::searchQuery($columns, $dynamicKeys, $dynamicColumn));
//            ->query(function ($builder, $value) use ($columns, $dynamicKeys, $dynamicColumn) {
//
//                foreach ($columns as $column) {
//                    $builder->orWhere($column, 'LIKE', '%' . $value . '%');
//                }
//
//                $builder->orWhereJsonLike($dynamicKeys, $value, $dynamicColumn);
//            });


        return $object;
    }
}
