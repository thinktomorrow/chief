<?php

namespace Thinktomorrow\Chief\Resource;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\Admin\Nav\BreadCrumb;
use Thinktomorrow\Chief\Admin\Nav\NavItem;
use Thinktomorrow\Chief\Table\TableResource;

// App specific resource methods
interface PageResource extends Resource, TableResource
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

    public function getIndexView(): View;

    public function getArchivedIndexView(): View;

    public function getIndexTitle(): string;

    public function getIndexHeaderContent(): ?string;

    public function getIndexBreadcrumb(): ?BreadCrumb;

    public function getIndexCardView(): string;

    public function getIndexCardTitle($model): string;

    public function getIndexCardContent($model): string;

    public function getIndexSidebar(): string; // content in sidebar.

    /**
     * Show the default filters, sorting and archive button in a sidebar section aside to the main rows.
     * If set to false, this content is shown beneath the rows. In case of a table layout, the filters
     * are shown above the table. Defaults to true.
     */
    public function showIndexSidebarAside(): bool;

    public function getIndexPagination(): int;

    public function showIndexOptionsColumn(): bool;

    /**
     * Indicate type of sortable id.
     * Options are int, string. Defaults to int.
     */
    public function getSortableType(): string;

    public function allowInlineSorting(): bool;

    /**
     * The class responsible for fetching the results for admin index pages.
     * @return string
     */
    public function indexRepository(): string;

    public function getNestableNodeLabels(): ?string;
}
