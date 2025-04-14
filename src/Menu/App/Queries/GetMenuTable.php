<?php

namespace Thinktomorrow\Chief\Menu\App\Queries;

use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Actions\RowAction;
use Thinktomorrow\Chief\Table\Columns\Column;
use Thinktomorrow\Chief\Table\Columns\ColumnBadge;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Sorters\TreeSort;
use Thinktomorrow\Chief\Table\Table;

class GetMenuTable
{
    public function getTable(string $menuId): Table
    {
        return Table::make()->query(function () use ($menuId) {
            return MenuItem::where('menu_id', $menuId);
        })->returnResultsAsTree()
            ->setTreeResource(new MenuItem)
            ->setTableReference(new Table\References\TableReference(static::class, 'getTable', [$menuId]))
            ->columns([
                ColumnText::make('label')
                    ->label('Label')
                    ->items(function ($model) {
                        return $model->getLabel();
                    })->link(function ($model) {
                        return route('chief.back.menuitem.edit', $model->getKey());
                    }),
                Column::items([
                    ColumnBadge::make('type')->label('Type')->items(function ($model) {
                        return match ($model->type) {
                            'internal' => 'Pagina',
                            'custom' => 'Eigen link',
                            default => 'Geen link',
                        };
                    }),
                    ColumnText::make('link')
                        ->label('Link')
                        ->items(function ($model) {
                            if ($model->type === 'nolink') {
                                return null;
                            }

                            return teaser($model->getUrl(), 48, '...');
                        })
                        ->openInNewTab()
                        ->link(function ($model) {
                            return $model->getUrl();
                        }),
                ]),
                ColumnBadge::make('status')
                    ->label('Status')
                    ->items(function ($model) {
                        return $model->getStatus()->value;
                    })->mapVariant([
                        'online' => 'green',
                        'offline' => 'red',
                    ]),
            ])
            ->actions([
                Action::make('create')
                    ->label('Menu item toevoegen')
                    ->prependIcon('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"> <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" /> </svg>')
                    ->link(route('chief.back.menuitem.create', $menuId)),
                Action::make('reorder')
                    ->label('Herschikken')
                    ->description('Hiermee kunt u de volgorde wijzigen.')
                    ->prependIcon('<x-chief::icon.sorting />')
                    ->link(route('chief.back.menus.reorder', $menuId)),
            ])
            ->rowActions([
                RowAction::make('edit')
                    ->link(function ($model) {
                        return route('chief.back.menuitem.edit', $model->getKey());
                    })
                    ->iconEdit()
                    ->variant('grey'),
            ])->sorters([
                TreeSort::default(),
            ]);
    }
}
