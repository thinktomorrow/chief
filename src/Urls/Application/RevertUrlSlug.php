<?php

namespace Thinktomorrow\Chief\Urls\Application;

use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Urls\UrlRecordNotFound;

/**
 * Revert slug to most recent redirect or empty it when no redirect exists.
 */
class RevertUrlSlug
{
    /** @var ProvidesUrl */
    private $model;

    private $existingRecords;

    public function __construct(ProvidesUrl $model)
    {
        $this->model = $model;
    }

    public function handle(string $locale): void
    {
        try {
            $currentUrlRecord = UrlRecord::findByModel($this->model, $locale);

            if ($recentRedirect = UrlRecord::findRecentRedirect($this->model, $locale)) {
                $recentRedirectSlug = $recentRedirect->slug;
                $recentRedirect->delete();

                $currentUrlRecord->replaceAndRedirect(['slug' => $recentRedirectSlug]);
            }
        } catch (UrlRecordNotFound $e) {
            // No url present so nothing to do here...
        }
    }
}
