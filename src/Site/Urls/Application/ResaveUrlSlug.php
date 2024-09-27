<?php

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\ValidationRules\UniqueUrlSlugRule;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class ResaveUrlSlug
{
    private SaveUrlSlugs $saveUrlSlugs;

    public function __construct(SaveUrlSlugs $saveUrlSlugs)
    {
        $this->saveUrlSlugs = $saveUrlSlugs;
    }

    /**
     * This will retrigger the save for a nested page, which will now
     * take the updated parent slug as its base url segment.
     */
    public function handle(Visitable $model, string $locale, array $strippableBaseUrlSegments = []): void
    {
        $currentSlug = UrlRecord::findSlugByModel($model, $locale);
        $strippedSlug = $currentSlug;

        // These are the base url segments of the parent model that should be removed.
        $strippableBaseUrlSegments = array_merge($strippableBaseUrlSegments, [$model->baseUrlSegment($locale)]);

        foreach ($strippableBaseUrlSegments as $baseUrlSegment) {
            if (0 === strpos($currentSlug, $baseUrlSegment . '/')) {
                $strippedSlug = substr($currentSlug, strlen($baseUrlSegment.'/'));
            }
        }

        // Former implementation
        //                $strippedSlug = false != strpos($currentSlug, '/') ? substr($currentSlug, strrpos($currentSlug, '/') + 1) : $currentSlug;

        // Avoid saving the new slug in case that this slug already exists on another model
        if (! (new UniqueUrlSlugRule($model, $model))->passes(null, [$locale => $strippedSlug])) {
            return;
        }

        $this->saveUrlSlugs->handle($model, [$locale => $strippedSlug]);
    }
}
