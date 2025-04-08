<?php

namespace Thinktomorrow\Chief\Urls\App\Queries;

use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\ChiefSites;

class GetBaseUrls
{
    /**
     * Get base urls for all locales
     */
    public function get(Visitable $model): array
    {
        $locales = ChiefSites::locales();

        $baseUrls = [];

        foreach ($locales as $locale) {
            $baseUrls[$locale] = $this->getBaseUrl($model, $locale);
        }

        return $baseUrls;
    }

    private function getBaseUrl(Visitable $model, string $locale): string
    {
        $baseUrlSegment = $model->baseUrlSegment($locale);

        return $model->resolveUrl($locale, [$baseUrlSegment]);
    }
}
