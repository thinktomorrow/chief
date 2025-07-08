<?php

namespace Thinktomorrow\Chief\Plugins\Seo\UI\Livewire;

use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Plugins\Seo\Models\HasAlt;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Columns\ColumnImage;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Filters\SearchFilter;
use Thinktomorrow\Chief\Table\Table;

class GetAssetsTable
{
    public function getTable(): Table
    {
        return Table::make()->query(function () {
            return Asset::query()
                ->whereNotIn('asset_type', ['youtube', 'vimeo', 'file'])
                ->whereHas('media', function ($query) {
                    $query->where('mime_type', 'LIKE', 'image/%');
                })->orderBy('created_at', 'desc');
        })
            ->setTableReference(new Table\References\TableReference(static::class, 'getTable'))
            ->listeners(['assetUpdated-seo-asset' => 'requestRefresh'])
            ->columns([
                // Get column image, filename and alt text (per locale) for each asset
                ColumnImage::make('image')
                    ->label('Afbeelding')
                    ->items(function ($model) {
                        return $model->exists('thumb') ? $model->getUrl('thumb') : null;
                    }),
                ColumnText::make('filename')
                    ->label('Bestandsnaam')
                    ->items(function ($model) {
                        return $model->getFileName();
                    }),
                ...array_map(function ($locale) {
                    return ColumnText::make('alt_'.$locale)
                        ->label(ChiefSites::adjective($locale).' alt tekst')
                        ->items(function ($model) use ($locale) {
                            if (! $model instanceof HasAlt) {
                                return '';
                            }

                            return $model->getAlt($locale) ?: '-';
                        });
                }, ChiefSites::locales()),
            ])
            ->actions([
                Action::make('export')
                    ->label('Alt teksten exporteren')
                    ->prependIcon('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"> <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" /> </svg>')
                    ->link(route('chief.back.menuitem.create', 'ddd')),
            ])
            ->rowActions([
                EditAssetRowAction::makeDefault()
                    ->iconEdit()
                    ->variant('grey'),
            ])
            ->filters([
                SearchFilter::make('title')
                    ->label('Tekst')
                    ->placeholder('Zoek op alt tekst')
                    ->query(function ($query) {}),
                //                SelectFilter::make(),
            ])
            ->sorters([
                //
            ]);
    }
}
