<?php

namespace Thinktomorrow\Chief\Table\Table\Presets;

use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;
use Thinktomorrow\Chief\Shared\Concerns\Sortable\Sortable;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Table\Actions\Presets\CreateModelAction;
use Thinktomorrow\Chief\Table\Actions\Presets\DuplicateModelAction;
use Thinktomorrow\Chief\Table\Actions\Presets\EditModelAction;
use Thinktomorrow\Chief\Table\Actions\Presets\OfflineStateBulkAction;
use Thinktomorrow\Chief\Table\Actions\Presets\OfflineStateRowAction;
use Thinktomorrow\Chief\Table\Actions\Presets\OnlineStateBulkAction;
use Thinktomorrow\Chief\Table\Actions\Presets\OnlineStateRowAction;
use Thinktomorrow\Chief\Table\Actions\Presets\ReorderAction;
use Thinktomorrow\Chief\Table\Actions\Presets\UnArchiveRowAction;
use Thinktomorrow\Chief\Table\Actions\Presets\VisitArchiveAction;
use Thinktomorrow\Chief\Table\Columns\ColumnBadge;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Columns\Presets\LinksColumnBadge;
use Thinktomorrow\Chief\Table\Columns\Presets\SiteLinksColumnBadge;
use Thinktomorrow\Chief\Table\Columns\Presets\SitesColumnBadge;
use Thinktomorrow\Chief\Table\Filters\Presets\OnlineStateFilter;
use Thinktomorrow\Chief\Table\Filters\Presets\SiteFilter;
use Thinktomorrow\Chief\Table\Filters\Presets\TitleFilter;
use Thinktomorrow\Chief\Table\Sorters\Sort;
use Thinktomorrow\Chief\Table\Table;
use Thinktomorrow\Chief\Table\Table\References\TableReference;

class PageTable extends Table
{
    public static function makeDefault(string $resourceKey): Table
    {
        $resource = app(Registry::class)->resource($resourceKey);
        $modelClass = $resource::modelClassName();
        $reflection = new \ReflectionClass($modelClass);

        $table = static::make()
            ->setTableReference(new TableReference(static::class, 'makeDefault', [$resourceKey]))
            ->resource($resourceKey)
            ->actions([
                CreateModelAction::makeDefault($resourceKey)->primary(),
                ...($reflection->hasMethod('scopeArchived') && $modelClass::archived()->count() > 0 ? [VisitArchiveAction::makeDefault($resourceKey)->tertiary()] : []),
                ...($reflection->implementsInterface(Sortable::class) ? [ReorderAction::makeDefault()->secondary()] : []),
            ])
            ->bulkActions([
                OnlineStateBulkAction::makeDefault($resourceKey),
                OfflineStateBulkAction::makeDefault($resourceKey),
            ])
            ->rowActions([
                EditModelAction::makeDefault($resourceKey)->primary(),
                OnlineStateRowAction::makeDefault($resourceKey)->tertiary(),
                OfflineStateRowAction::makeDefault($resourceKey)->tertiary(),
                UnArchiveRowAction::makeDefault($resourceKey)->tertiary(),
                DuplicateModelAction::makeDefault($resourceKey)->tertiary(),
            ])
            ->filters([
                ...($reflection->implementsInterface(HasAllowedSites::class) ? [SiteFilter::makeDefault($resourceKey)->primary()] : []),
                TitleFilter::makeDefault(),
                OnlineStateFilter::makeDefault()->secondary(),
            ])
            ->columns([
                ColumnText::make('title')->label('Titel')->link(function ($model) use ($resourceKey) {
                    return '/admin/'.$resourceKey.'/'.$model->getKey().'/edit';
                })->disallowColumnSelection()
                    ->tease(54, '...'),
                ColumnBadge::make('current_state')->pageStates()->label('Status')->columnNotSelectedByDefault(),
                ...(($reflection->implementsInterface(HasAllowedSites::class) && $reflection->implementsInterface(Visitable::class)) ? [SiteLinksColumnBadge::makeDefault()] : []),
                ...(($reflection->implementsInterface(HasAllowedSites::class) && ! $reflection->implementsInterface(Visitable::class)) ? [SitesColumnBadge::makeDefault()] : []),
                ...(($reflection->implementsInterface(Visitable::class) && ! $reflection->implementsInterface(HasAllowedSites::class)) ? [LinksColumnBadge::makeDefault()] : []),
            ])
            ->sorters([
                Sort::make('title_asc')->label('Titel - A-Z')->query(function ($builder) {
                    $builder->orderByRaw('json_unquote(json_extract(`values`, \'$."title"."nl"\')) ASC');
                }),
                Sort::make('title_desc')->label('Titel - Z-A')->query(function ($builder) {
                    $builder->orderByRaw('json_unquote(json_extract(`values`, \'$."title"."nl"\')) DESC');
                }),
                Sort::make('updated_at_desc')->label('Laatst aangepast')->query(function ($builder) {
                    $builder->orderBy('updated_at', 'DESC');
                }),
            ]);

        if ($reflection->implementsInterface(Taggable::class)) {
            $table->tagPresets($resourceKey);
        }

        if ($reflection->implementsInterface(Visitable::class)) {
            $table->visitablePresets($resourceKey);
        }

        if ($reflection->implementsInterface(Sortable::class)) {
            $table->sortablePresets();
        }

        // Check if model has updated_at timestamp
        if (! (new $modelClass)->usesTimestamps()) {
            $table->removeColumn('updated_at');
        }

        return $table;
    }
}
