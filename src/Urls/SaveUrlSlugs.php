<?php

namespace Thinktomorrow\Chief\Urls;

use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;

class SaveUrlSlugs
{
    /**
     * @var ProvidesUrl
     */
    private $model;

    public function __construct(ProvidesUrl $model)
    {
        $this->model = $model;
    }

    public function handle(array $slugs): void
    {
        $existingRecords = UrlRecord::getByModel($this->model);

        $slugs = $this->normalizeSlugs($slugs);

        foreach($slugs as $locale => $slug){

            $locale = ($locale == UrlAssistant::WILDCARD) ? null : $locale;

            // Existing ones for this locale?
            $targetedExistingRecords = $existingRecords->filter(function($record) use($locale){
                return ($record->locale == $locale && !$record->isRedirect());
            });

            // In the case where we have any redirects that match the given slug, we need to
            // remove the redirect record in favor of the newly added one.
            $this->deleteIdenticalRedirects($existingRecords, $locale, $slug);

            if($targetedExistingRecords->isEmpty()){
                $this->createRecord($locale,$slug);
            }
            else{
                // Only replace the existing records that differ from the current passed slugs
                $targetedExistingRecords->each(function($existingRecord) use($slug){
                    if($existingRecord->slug != $slug){
                        $existingRecord->replace(['slug' => $slug]);
                    }
                });
            }
        }
    }

    /**
     * Prepend base url segments to each passed slug if needed
     * If model provides localized base url segments, we will also expand the wildcard slug
     * to match all given locales so that the base url segment is localized as well.
     *
     * @param array $slugs
     * @return array
     */
    private function normalizeSlugs(array $slugs): array
    {
        $availableLocales = $this->model->availableLocales();

        $availableBaseUrlSegments = [];
        foreach($availableLocales as $locale){
            $availableBaseUrlSegments[$locale] = $this->model->baseUrlSegment($locale);
        }

        $expectsLocalizedBaseUrlSegments = count(array_unique($availableBaseUrlSegments)) > 1;
        $containsWildCard = false;

        $passedLocales = [];
        foreach($slugs as $locale => $slug){
            if($slug && $locale !== UrlAssistant::WILDCARD){
                $passedLocales[] = $locale;
            }
            if($slug && $locale == UrlAssistant::WILDCARD){
                $containsWildCard = true;
            }
        }

        if($expectsLocalizedBaseUrlSegments && $containsWildCard){
            foreach($availableLocales as $availableLocale){
                if( ! array_search($availableLocale, $passedLocales)){
                    $slugs[$availableLocale] = $slugs[UrlAssistant::WILDCARD];
                }
            }
        }

        foreach($slugs as $locale => $slug){
            $slugs[$locale] = trim($this->model->baseUrlSegment($locale) . '/' . $slug, '/');
        }

        return $slugs;
    }

    private function createRecord($locale, $slug)
    {
        UrlRecord::create([
            'locale'     => $locale,
            'slug'       => $slug,
            'model_type' => $this->model->getMorphClass(),
            'model_id'   => $this->model->id,
        ]);
    }

    /**
     * @param $existingRecords
     * @param $locale
     * @param $slug
     */
    private function deleteIdenticalRedirects($existingRecords, $locale, $slug): void
    {
        $existingRecords->filter(function ($record) use ($locale) {
            return ($record->locale == $locale && $record->isRedirect());
        })->each(function ($existingRecord) use ($slug) {
            if ($existingRecord->slug == $slug) {
                $existingRecord->delete();
            }
        });
    }
}