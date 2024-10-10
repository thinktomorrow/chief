<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use RuntimeException;
use Thinktomorrow\Chief\Admin\Nav\BreadCrumb;
use Thinktomorrow\Chief\Admin\Nav\NavItem;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Plugins\Tags\App\Taggable\Taggable;
use Thinktomorrow\Chief\Table\Actions\Presets\AttachTagAction;
use Thinktomorrow\Chief\Table\Actions\Presets\CreateModelAction;
use Thinktomorrow\Chief\Table\Actions\Presets\DetachTagAction;
use Thinktomorrow\Chief\Table\Actions\Presets\EditModelAction;
use Thinktomorrow\Chief\Table\Actions\Presets\VisitArchiveAction;
use Thinktomorrow\Chief\Table\Columns\ColumnBadge;
use Thinktomorrow\Chief\Table\Columns\ColumnDate;
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
        $table = Table::make()
            ->resource(static::resourceKey())
            ->bulkActions([
                AttachTagAction::makeDefault(static::resourceKey()),
                DetachTagAction::makeDefault(static::resourceKey()),
            ])
            ->actions([
                CreateModelAction::makeDefault(static::resourceKey()),
                VisitArchiveAction::makeDefault(static::resourceKey()),
            ])
            ->rowActions([
                EditModelAction::makeDefault(static::resourceKey()),
            ])
            ->filters([
                TitleFilter::makeDefault(),
                OnlineStateFilter::makeDefault()->main(),
            ])
            ->columns([
                ColumnText::make('title')->label('Titel')->link(function ($model) {
                    return '/admin/' . static::resourceKey() . '/' . $model->getKey() . '/edit';
                }),
                ColumnBadge::make('current_state')->pageStates()->label('Status'),
                ColumnDate::make('updated_at')->label('Aangepast')->format('d/m/Y H:i'),
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

        // Check support for tags on model
        if ((new \ReflectionClass(static::modelClassName()))->implementsInterface(Taggable::class)) {
            $table->tagPresets(static::resourceKey());
        }

        return $table;
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
        return Blade::render('<x-chief::icon.folder-library />');
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
