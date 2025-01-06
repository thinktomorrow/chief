<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Admin\Nav\BreadCrumb;
use Thinktomorrow\Chief\Admin\Nav\NavItem;
use Thinktomorrow\Chief\Table\Table;

// App specific resource methods
interface PageResource extends Resource
{
    // Nav
    public function getNavItem(): ?NavItem;

    // Page
    public function getCreatePageView(): View;

    public function getRedirectAfterCreate($model): ?string;

    public function getPageView(): View;

    public function getPageBreadCrumb(): ?BreadCrumb;

    public function getPageTitle($model): string;

    public function getPageTitleForSelect($model): string;

    public function getTitleAttributeKey(): string; // Which attribute identifies the title value - defaults to 'title' ($model->title).

    public function getIndexTable(): Table;
    public function getReorderTable(): Table;

    public function getIndexView(): View;
    public function getIndexTitle(): string;
    public function getIndexDescription(): ?string;
    public function getIndexHeaderContent(): ?string;
    public function getIndexBreadcrumb(): ?BreadCrumb;
    public function getIndexSidebar(): string; // content in sidebar.

    /**
     * Indicate type of sortable id.
     * Options are int, string. Defaults to int.
     */
    public function getSortableType(): string;

    public function allowInlineSorting(): bool;
}
