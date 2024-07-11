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
    public function handle(Visitable $model, string $locale, array $baseUrlSegments = []): void
    {
        $currentSlug = UrlRecord::findSlugByModel($model, $locale);
        $strippedSlug = $currentSlug;

        // These are the base url segments of the parent model that should be removed.
        // Not faulty free...
        $baseUrlSegments = array_merge($baseUrlSegments, [$model->baseUrlSegment($locale)]);

        foreach($baseUrlSegments as $baseUrlSegment) {
            if(0 === strpos($currentSlug, $baseUrlSegment . '/')) {
                $strippedSlug = substr($currentSlug, strlen($baseUrlSegment.'/'));
            }
        }

//        if(str_contains($strippedSlug, 'loungeset')) {
//            dd($baseUrlSegments, $currentSlug, $strippedSlug);
//        }

//dd($strippedSlug);
//        $strippedSlug = 0 === strpos($currentSlug, $baseUrlSegment . '/')
//            ? substr($currentSlug, strlen($baseUrlSegment.'/'))
//            : $currentSlug;
//
//        $strippedSlug = false != strpos($currentSlug, '/') ? substr($currentSlug, strrpos($currentSlug, '/') + 1) : $currentSlug;

        // Avoid saving the new slug in case that this slug already exists on another model
        if (! (new UniqueUrlSlugRule($model, $model))->passes(null, [$locale => $strippedSlug])) {
            return;
        }

        $this->saveUrlSlugs->handle($model, [$locale => $strippedSlug]);
    }
}
