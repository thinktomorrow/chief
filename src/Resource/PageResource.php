<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Admin\Nav\BreadCrumb;
use Thinktomorrow\Chief\Admin\Nav\NavItem;

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

    // Index
    public function getIndexView(): View;
    public function getIndexTitle(): string;
    public function getIndexBreadcrumb(): ?BreadCrumb;
    public function getIndexCardView(): string;
    public function getIndexCardTitle($model): string;
    public function getIndexCardContent($model): string;
    public function getIndexSidebar(): string; // content in sidebar.
    public function getIndexPagination(): int;
}
