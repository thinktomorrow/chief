<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

/**
 * Revert slug to most recent redirect or empty it when no redirect exists.
 */
final class RevertUrlSlug
{
    public function handle(Visitable $model, string $siteId): void
    {
        if ($recentRedirect = UrlRecord::findRecentRedirect($model, $siteId)) {
            $recentRedirect->revert();
        }
    }
}
