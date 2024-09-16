<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use RuntimeException;
use Thinktomorrow\Chief\Admin\Nav\BreadCrumb;
use Thinktomorrow\Chief\Admin\Nav\NavItem;
use Thinktomorrow\Chief\Forms\Dialogs\Dialog;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Plugins\Tags\App\Read\TagReadRepository;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\TaggableRepository;
use Thinktomorrow\Chief\Table\Actions\Action;
use Thinktomorrow\Chief\Table\Columns\ColumnBadge;
use Thinktomorrow\Chief\Table\Columns\ColumnDate;
use Thinktomorrow\Chief\Table\Columns\ColumnText;
use Thinktomorrow\Chief\Table\Filters\ButtonGroupFilter;
use Thinktomorrow\Chief\Table\Filters\SelectFilter;
use Thinktomorrow\Chief\Table\Filters\TextFilter;
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
            ->bulkActions([
                Action::make('tag')
                    ->label('Tag deze selectie')
                    ->dialog(
                        Dialog::make('tagModal')
                            ->title('Voeg tags toe aan selectie')
                            // TODO(ben): make it so that the subtitle of a bulk action modal displays the amount of selected items
                            ->subTitle(':count items geselecteerd')
                            ->content('
                                <p>
                                    Tags helpen je om pagina\'s te groeperen en te filteren.
                                    Kies alle tags die je wil toevoegen aan deze pagina\'s.
                                </p>
                            ')
                            ->form([
                                MultiSelect::make('tags')
                                    ->required()
                                    ->multiple()
                                    ->options(fn () => app(TagReadRepository::class)->getAllForSelect()),
                            ])
                            ->button('Toevoegen')
                    )->effect(function ($formData, $data) {

                        $tagIds = (array) ($formData['tags'] ?? []);
                        $modelIds = $data['items'];

                        app(TaggableRepository::class)->attachTags($modelIds, $tagIds);
                    }),
            ])
            ->actions([
                Action::make('export')
                    ->label('Exporteer')

                    // MODAL
                    ->dialog(
                        Dialog::make('test')
                            ->title('Export')
                            ->subtitle('Exporteer deze pagina naar csv formaat')
                            ->content('Een csv bestand kan je gebruiken om de pagina te importeren in een ander systeem.')
                            ->form([
                                Text::make('title')->locales(['nl', 'fr'])->label('Titel')->required(),
                                Image::make('image')->label('Afbeelding'),
                            ])
                            ->button('Exporteer')
                    )

                    // CONFIRM MODAL
                    //->modal(ConfirmModal::make())
                    //->confirm('Weet je zeker dat je deze pagina wilt exporteren?', 'Ben je zeker?', 'Ja, Exporteer')

                    ->hidden()
                    ->appendIcon('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"> <path d="M9.25 13.25a.75.75 0 0 0 1.5 0V4.636l2.955 3.129a.75.75 0 0 0 1.09-1.03l-4.25-4.5a.75.75 0 0 0-1.09 0l-4.25 4.5a.75.75 0 1 0 1.09 1.03L9.25 4.636v8.614Z" /> <path d="M3.5 12.75a.75.75 0 0 0-1.5 0v2.5A2.75 2.75 0 0 0 4.75 18h10.5A2.75 2.75 0 0 0 18 15.25v-2.5a.75.75 0 0 0-1.5 0v2.5c0 .69-.56 1.25-1.25 1.25H4.75c-.69 0-1.25-.56-1.25-1.25v-2.5Z" /> </svg>')
                    ->effect(function ($formData) {
                        dd($formData);

                        return 'export';
                    }),
                Action::make('create')
                    ->label('Voeg pagina toe')
                    // Primary, secondary
                    // visible, hidden // Or from previous visit or by preference
                    ->prependIcon('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5"> <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" /> </svg>')
                    ->link('/admin/catalogpage/create'),
            ])
            ->filters([
                TextFilter::make('title')->query(function ($builder, $value) {
                    $builder->whereJsonLike(['title'], $value);
                }),
                ButtonGroupFilter::make('current_state')->label('Status')->options([
                    '' => 'Alle',
                    'published' => 'Online',
                    'draft' => 'Offline',
                    'archived' => 'Gearchiveerd',
                ])->value(''),
                //SelectFilter::make('brands')->label('Status')->options(),
                SelectFilter::make('current_state')->label('Status')->options([
                    'published' => 'Online',
                    'draft' => 'Offline',
                    'archived' => 'Gearchiveerd',
                ]),
            ])
            ->columns([
                ColumnText::make('title')->label('Titel')->link(function ($model) {
                    return '/admin/' . static::resourceKey() . '/' . $model->getKey() . '/edit';
                }),
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
            });
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

        return new BreadCrumb('Overzicht', $this->manager->route('index'));
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
