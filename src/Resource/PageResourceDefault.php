<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use RuntimeException;
use Thinktomorrow\Chief\Admin\Nav\BreadCrumb;
use Thinktomorrow\Chief\Admin\Nav\NavItem;
use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Actions\Presets\AttachTagAction;
use Thinktomorrow\Chief\Table\Actions\Presets\CreateModelAction;
use Thinktomorrow\Chief\Table\Actions\Presets\DetachTagAction;
use Thinktomorrow\Chief\Table\Actions\Presets\VisitArchiveAction;
use Thinktomorrow\Chief\Table\Columns\Column;
use Thinktomorrow\Chief\Table\Columns\ColumnBadge;
use Thinktomorrow\Chief\Table\Columns\ColumnDate;
use Thinktomorrow\Chief\Table\Columns\ColumnImage;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Filters\Presets\OnlineStateFilter;
use Thinktomorrow\Chief\Table\Filters\Presets\TitleFilter;
use Thinktomorrow\Chief\Table\Sorters\Sort;
use Thinktomorrow\Chief\Table\Table;

trait PageResourceDefault
{
    use ResourceDefault;

    public function getNavItem(): ?NavItem
    {
        $this->assertManager();

        if (! $this->manager->can('index')) {
            return null;
        }

        return new NavItem(
            $this->getIndexTitle(),
            $this->manager->route('index'),
            $this->getNavTags(),
            $this->getNavIcon()
        );
    }

    private function assertManager(): void
    {
        if (! $this->manager) {
            throw new RuntimeException('For calling this method a Manager instance should be set to this resource.');
        }
    }

    /**
     * This is a temporary method to get the index table. In a future release of Chief, a table will be
     * configured on a Page class instead.
     */
    public function getIndexTable(): Table
    {
        // Position of general actions: footer
        // Interaction with Livewire component
        //        return Table::make()
        //            ->resource(static::resourceKey())
        ////            ->treeLabelColumn('title')
        //            ->columns([
        //                'title', 'tags.label', 'current_state',
        //            ])
        ////            ->rowView('chief-table::rows.list-item')
        //            // Data options: query, model, relation, rows
        ////            ->model(static::modelClassName()) // Entire model or relation or query or rows...
        ////            ->relation('modelClass', 'id', 'tags')
        //
        //            // Convenience create action
        ////            ->withCreateAction() // Via modal
        ////            ->withInlineCreateAction() // Inline create instead of modal (ideal for small forms)
        //            // How to know which fields? convenience fields method?
        //
        //                // Better to have a resource class for this...
        //                // But then how to set the 'pivot' fields?
        ////            ->pivotFields(function($model) {
        ////                return [
        ////                    Text::make('title')->label('Titel'),
        ////                    Image::make('image')->label('Afbeelding'),
        ////                ];
        ////            })
        //
        //        ;


        return Table::make()
            ->resource(static::resourceKey())
            ->addQuery(function ($builder) {
                $builder->with(['tags']);
            })
            ->bulkActions([
                AttachTagAction::makeDefault(static::resourceKey()),
                DetachTagAction::makeDefault(static::resourceKey()),
            ])
            ->actions([
                CreateModelAction::makeDefault(static::resourceKey()),
                VisitArchiveAction::makeDefault(static::resourceKey()),
            ])
            ->filters([
                TitleFilter::makeDefault(),
                OnlineStateFilter::makeDefault()->main(),
            ])
            ->columns([
                Column::items([
                    ColumnText::make('title')->label('Titel')->link(function ($model) {
                        return '/admin/' . static::resourceKey() . '/' . $model->getKey() . '/edit';
                    }),
                ]),
                ColumnBadge::make('tags.label')->label('tags'),

                // ColumnText::make('seo_title')->label('SEO Titel'),
                ColumnBadge::make('current_state')->pageStates()->label('Status'),
                ColumnDate::make('created_at')->label('Aangemaakt op')->format('d/m/Y H:i'),
            ])
            ->sorters([
                Sort::make('title_asc')->label('Titel - A-Z')->query(function ($builder) {
                    $builder->orderByRaw('json_unquote(json_extract(`values`, \'$."title"."nl"\')) ASC');
                }),
                Sort::make('title_desc')->label('Titel - Z-A')->query(function ($builder) {
                    $builder->orderByRaw('json_unquote(json_extract(`values`, \'$."title"."nl"\')) DESC');
                }),
                Sort::make('created_at_desc')->label('Datum - DESC')->query(function ($builder) {
                    $builder->orderBy('created_at', 'DESC');
                }),
                Sort::make('created_at_asc')->label('Datum - ASC')->query(function ($builder) {
                    $builder->orderBy('created_at', 'ASC');
                }),
            ]);
    }

    public function getArchivedIndexTable(): Table
    {
        return $this->getIndexTable()
            ->addQuery(function ($builder) {
                $builder->archived();
            })
            ->removeFilter('current_state')
            ->removeAction('create')
            ->removeAction('archive-index');
    }

    public function getOtherIndexTable(): Table
    {
        // Position of general actions: footer
        // Interaction with Livewire component
        return Table::make()
            ->resource(static::resourceKey())
            //            ->treeLabelColumn('title')
            ->columns([
                'title',
            ])->sorters([
                Sort::make('title_asc')->label('Titel - A-Z')->query(function ($builder) {
                    $builder->orderByRaw('json_unquote(json_extract(`values`, \'$."title"."nl"\')) ASC');
                }),
                Sort::make('title_desc')->label('Titel - Z-A')->query(function ($builder) {
                    $builder->orderByRaw('json_unquote(json_extract(`values`, \'$."title"."nl"\')) DESC');
                }),
                Sort::make('created_at_desc')->label('Datum - DESC')->query(function ($builder) {
                    $builder->orderBy('created_at', 'DESC');
                }),
                Sort::make('created_at_asc')->label('Datum - ASC')->query(function ($builder) {
                    $builder->orderBy('created_at', 'ASC');
                }),
            ]);
    }

    public function getIndexTitle(): string
    {
        return ucfirst((new ResourceKeyFormat(static::modelClassName()))->getPluralLabel());
    }

    public function getIndexDescription(): ?string
    {
        return null;
    }

    protected function getNavTags(): array
    {
        return ['nav'];
    }

    protected function getNavIcon(): string
    {
        return '<svg><use xlink:href="#icon-rectangle-stack"></use></svg>';
    }

    public function getCreatePageView(): View
    {
        return view('chief::manager.create');
    }

    public function getRedirectAfterCreate($model): ?string
    {
        return $this->manager->route('edit', $model);
    }

    public function getPageView(): View
    {
        return view('chief::manager.edit');
    }

    public function getPageBreadCrumb(): ?BreadCrumb
    {
        $this->assertManager();

        if (! $this->manager->can('index')) {
            return null;
        }

        return null;
    }

    public function getIndexHeaderContent(): ?string
    {
        return null;
    }

    public function getPageTitleForSelect($model): string
    {
        $suffix = $model instanceof StatefulContract && ! $model->inOnlineState() ? ' [offline]' : '';

        return $this->getPageTitle($model) . $suffix;
    }

    public function getPageTitle($model): string
    {
        if (isset($model->{$this->getTitleAttributeKey()}) && $model->{$this->getTitleAttributeKey()}) {
            return $model->{$this->getTitleAttributeKey()};
        }

        return $this->getLabel();
    }

    public function getTitleAttributeKey(): string
    {
        return 'title';
    }

    public function getIndexView(): View
    {
        return view('chief::manager.index');
    }

    public function getIndexBreadcrumb(): ?BreadCrumb
    {
        return null;
    }

    public function getIndexCardView(): string
    {
        return 'chief::manager._index._card';
    }

    public function getIndexCardTitle($model): string
    {
        return $this->getPageTitle($model);
    }

    public function getIndexCardContent($model): string
    {
        return view('chief::manager._index._card-content', ['model' => $model])->render();
    }

    public function getIndexSidebar(): string
    {
        return '';
    }

    public function showIndexSidebarAside(): bool
    {
        return true;
    }

    public function showIndexOptionsColumn(): bool
    {
        return true;
    }

    public function getIndexPagination(): int
    {
        return 20;
    }

    public function getSortableType(): string
    {
        return 'int';
    }

    public function allowInlineSorting(): bool
    {
        return false;
    }
}
