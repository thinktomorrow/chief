<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Admin\Nav\BreadCrumb;
use Thinktomorrow\Chief\Admin\Nav\NavItem;

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

        return new BreadCrumb('Terug naar overzicht', $this->manager->route('index'));
    }

    public function getPageTitle($model): string
    {
        if (isset($model->title) && $model->title) {
            return $model->title;
        }

        return $this->getLabel();
    }

    public function getIndexTitle(): string
    {
        return ucfirst((new ResourceKeyFormat(static::modelClassName()))->getPluralLabel()) ;
    }

    public function getIndexBreadcrumb(): ?BreadCrumb
    {
        return null;
    }

    public function getIndexCardView(): string
    {
        return 'chief::manager._index._card';
    }

    public function getIndexCardContent($model): string
    {
        return view('chief::manager._index._card-content', ['model' => $model])->render();
    }


    public function getIndexSidebar(): string
    {
        return '';
    }

    public function getIndexPagination(): int
    {
        return 20;
    }

    public function getTitleAttributeKey(): string
    {
        return 'title';
    }

    protected function getNavIcon(): ?string
    {
        return '<svg><use xlink:href="#icon-collection"></use></svg>';
    }

    protected function getNavTags(): array
    {
        return ['nav'];
    }

    private function assertManager(): void
    {
        if (! $this->manager) {
            throw new \RuntimeException('For calling this method a Manager instance should be set to this resource.');
        }
    }
}
