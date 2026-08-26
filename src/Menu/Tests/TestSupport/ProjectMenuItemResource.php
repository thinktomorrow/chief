<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu\Tests\TestSupport;

use Thinktomorrow\Chief\Forms\Fields\Checkbox;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Menu\Resources\DefaultMenuItemResource;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Columns\Presets\AssetColumnImage;
use Thinktomorrow\Chief\Table\Table;

class ProjectMenuItemResource extends DefaultMenuItemResource
{
    public function fields($model): iterable
    {
        yield Textarea::make('description')
            ->label('Beschrijving')
            ->locales(['nl', 'en'])
            ->required();

        yield Checkbox::make('show_children')
            ->label('Onderliggende items tonen')
            ->multiple(false)
            ->options([1 => 'Tonen']);

        yield Image::make('thumbnail')->label('Thumbnail');
    }

    public function configureTable(Table $table): Table
    {
        return parent::configureTable($table)->columns([
            AssetColumnImage::makeDefault('thumbnail')->label('Thumbnail'),
            ColumnText::make('description')
                ->label('Beschrijving')
                ->items(fn (MenuItem $menuItem, ?string $locale) => $menuItem->dynamic('description', $locale ?: app()->getLocale())),
        ]);
    }
}
