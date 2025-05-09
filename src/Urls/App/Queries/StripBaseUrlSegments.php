<?php

namespace Thinktomorrow\Chief\Urls\App\Queries;

use Thinktomorrow\Chief\Site\Visitable\Visitable;

class StripBaseUrlSegments
{
    public static function strip(Visitable $model, string $site, string $slug, array $blacklist): string
    {
        $strippedSlug = $slug;

        // These are the base url segments of the parent model that should be removed.
        $blacklist = array_merge($blacklist, [$model->baseUrlSegment($site)]);

        foreach ($blacklist as $baseUrlSegment) {
            if (strpos($slug, $baseUrlSegment.'/') === 0) {
                $strippedSlug = substr($slug, strlen($baseUrlSegment.'/'));
            }
        }

        return $strippedSlug;
    }
}
