<?php

namespace Thinktomorrow\Chief\Table\Filters\Presets;

use Thinktomorrow\Chief\Table\Filters\SearchFilter;

class TitleFilter extends SearchFilter
{
    public static function makeDefault(array $columns = [], array $dynamicKeys = ['title'], string $dynamicColumn = 'values'): self
    {
        return static::make('title')
            ->label('Titel')
            ->placeholder('Zoek op titel')
            ->description('Zoek op pagina titel')
            ->query(FilterPresets::searchQuery($columns, $dynamicKeys, $dynamicColumn));
    }
}
