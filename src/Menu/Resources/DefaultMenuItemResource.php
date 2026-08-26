<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Resources;

use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Resource\ResourceDefault;
use Thinktomorrow\Chief\Table\Filters\Presets\FilterPresets;
use Thinktomorrow\Chief\Table\Table;

class DefaultMenuItemResource implements MenuItemResource
{
    use ResourceDefault;

    public static function modelClassName(): string
    {
        return MenuItem::class;
    }

    public function fields($model): iterable
    {
        return [];
    }

    public function configureTable(Table $table): Table
    {
        return $table->filters([
            FilterPresets::search('search', dynamicKeys: ['label', 'url'])
                ->label('Zoeken')
                ->placeholder('Zoek op label of URL'),
        ]);
    }
}
