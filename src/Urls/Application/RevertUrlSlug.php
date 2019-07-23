<?php

namespace Thinktomorrow\Chief\Urls\Application;

use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Urls\UrlRecordNotFound;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;

/**
 * Revert slug to most recent redirect or empty it when no redirect exists.
 */
class RevertUrlSlug
{
    /** @var ProvidesUrl */
    private $model;

    public function __construct(ProvidesUrl $model)
    {
        $this->model = $model;
    }

    public function handle(string $locale): void
    {
        if ($recentRedirect = UrlRecord::findRecentRedirect($this->model, $locale)) {
            $recentRedirect->revert();
        }
    }
}
