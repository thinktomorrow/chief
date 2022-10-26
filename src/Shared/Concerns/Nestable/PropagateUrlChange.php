<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable;

use Thinktomorrow\Chief\Site\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

class PropagateUrlChange
{
    private array $locales;

    public function __construct()
    {
        $this->locales = config('chief.locales', []);
    }

    /**
     * When a nestable url gets saved, we'll make sure that all
     * underlying children will have their urls updated.
     */
    public function handle(NestedNode & Visitable $model): void
    {
        // TODO: how to set locales per page??? Now we always take the general locales from chief
        foreach ($this->locales as $locale) {
            $this->resaveUrlSlug($model, $locale);

            foreach ($model->getChildNodes() as $child) {
                $child->propagateBaseUrlSegment();
            }
        }
    }

    /**
     * This will retrigger the save for a nested page, which will now
     * take the updated parent slug as its base url segment.
     */
    private function resaveUrlSlug(NestedNode & Visitable $model, string $locale): void
    {
//        $currentSlug = ($urlRecord = UrlRecord::findByModel($model, $locale)) ? $urlRecord->slug : '';
        $currentSlug = $model->getUrlSlug();

        $strippedSlug = false != strpos($currentSlug, '/') ? substr($currentSlug, strrpos($currentSlug, '/') + 1) : $currentSlug;

        (new SaveUrlSlugs())->handle($model, [$locale => $strippedSlug]);
    }

//    protected function getCurrentSlug(): string
//    {
//        // TODO: for each locales... not only NL
//        return ($urlRecord = UrlRecord::findByModel($this, 'nl')) ? $urlRecord->slug : '';
//    }
}
