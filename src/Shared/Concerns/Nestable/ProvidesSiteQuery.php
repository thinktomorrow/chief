<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

interface ProvidesSiteQuery
{
    /**
     * The base query for fetching this model results for the site. This usually
     * includes a default sorting and clauses to only return published models.
     */
    public static function baseSiteQuery(): Builder;

    public static function getRoots(array $ignoredIds = []): Collection;

    public static function getChildrenOf(string $parentId, array $ignoredIds = []): Collection;

    public static function getAllChildrenOf(string $parentId, array $ignoredIds = []): Collection;

    public function getBreadCrumbs(): array;
}
