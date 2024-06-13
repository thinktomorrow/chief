<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use RuntimeException;
use Thinktomorrow\Chief\Admin\Nav\BreadCrumb;
use Thinktomorrow\Chief\Admin\Nav\NavItem;
use Thinktomorrow\Chief\ManagedModels\Repository\EloquentIndexRepository;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\Table\TableResourceDefault;

trait PageResourceDefault
{
    use ResourceDefault;
    use TableResourceDefault;

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
        // TODO: temp
        if ($this->getIndexViewType() == 'listing') {
            return view('chief-table-new::index');
        }

        if ($this->getIndexViewType() == 'table') {
            return ($this instanceof Nestable)
                ? view('chief-table::nestable.index')
                : view('chief-table::index');
        }

        return view('chief::manager.index');
    }

    /**
     * Default type of index: options are:
     * index (default), table
     */
    protected function getIndexViewType(): string
    {
        return 'table';
    }

    public function getArchivedIndexView(): View
    {
        if ($this->getIndexViewType() == 'table') {
            return view('chief-table::index');
        }

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

    public function indexRepository(): string
    {
        return EloquentIndexRepository::class;
    }

    public function getNestableNodeLabels(): ?string
    {
        return null;
    }
}
