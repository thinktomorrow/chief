<?php

namespace Thinktomorrow\Chief\Urls;

use Thinktomorrow\Chief\Management\Assistants\UrlAssistant;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;

class SaveUrlSlugs
{
    /** @var ProvidesUrl */
    private $model;

    private $existingRecords;

    public function __construct(ProvidesUrl $model)
    {
        $this->model = $model;
    }

    public function handle(array $slugs): void
    {
        $this->existingRecords = UrlRecord::getByModel($this->model);

        foreach($slugs as $locale => $slug){

            if($locale == UrlAssistant::WILDCARD){

                $this->saveWildcardSlug($this->remainingLocales($slugs), $slug);
                continue;
            }

            if(!$slug){
                $this->deleteRecord($locale);
                continue;
            }

            $this->saveRecord($locale, $this->prependBaseUrlSegment($slug, $locale));
        }
    }

    /**
     * Wildcard slug is created for all passed locales
     *
     * @param array $locales
     * @param string $slug
     */
    private function saveWildcardSlug(array $locales, ?string $slug)
    {
        foreach($locales as $locale)
        {
            if(!$slug) {
                $this->deleteRecord($locale, true);
                continue;
            }

            $this->saveRecord($locale, $this->prependBaseUrlSegment($slug, $locale), true);
        }
    }

    private function deleteRecord(string $locale, bool $onlyWildcards = false)
    {
        return $this->saveRecord($locale, null, $onlyWildcards);
    }

    private function saveRecord(string $locale, ?string $slug, bool $savingAsWildcard = false)
    {
        // Existing ones for this locale?
        $recordsWithSameLocale = $this->existingRecords->filter(function($record) use($locale, $savingAsWildcard){
            return (
                $record->locale == $locale &&
                !$record->isRedirect()
            );
        });

        // In the case where we have any redirects that match the given slug, we need to
        // remove the redirect record in favour of the newly added one.
        $this->deleteIdenticalRedirects($this->existingRecords, $locale, $slug, $savingAsWildcard);

        // If slug entry is left empty, all existing records will be deleted
        if(!$slug){
            $recordsWithSameLocale->filter(function($existingRecord) use($savingAsWildcard){
                return ($existingRecord->isManagedAsWildcard() === $savingAsWildcard);
            })->each(function($existingRecord){
                $existingRecord->delete();
            });
        }
        elseif($recordsWithSameLocale->isEmpty()){
            $this->createRecord($locale, $slug, $savingAsWildcard);
        }
        else{
            // Only replace the existing records that differ from the current passed slugs
            $recordsWithSameLocale->each(function($existingRecord) use($slug, $savingAsWildcard){
                if($existingRecord->slug != $slug){
                    $existingRecord->replace(['slug' => $slug, 'managed_as_wildcard' => $savingAsWildcard]);
                }
            });
        }
    }

    private function createRecord($locale, $slug, bool $managedAsWildcard = false)
    {
        UrlRecord::create([
            'locale'              => $locale,
            'managed_as_wildcard' => $managedAsWildcard,
            'slug'                => $slug,
            'model_type'          => $this->model->getMorphClass(),
            'model_id'            => $this->model->id,
        ]);
    }

    /**
     * @param $existingRecords
     * @param $locale
     * @param $slug
     * @param bool $managedAsWildcard
     */
    private function deleteIdenticalRedirects($existingRecords, $locale, $slug, bool $managedAsWildcard = false): void
    {
        $existingRecords->filter(function ($record) use ($locale, $managedAsWildcard) {
            return (
                $record->locale == $locale &&
                $managedAsWildcard === $record->isManagedAsWildcard() &&
                $record->isRedirect()
            );
        })->each(function ($existingRecord) use ($slug) {
            if ($existingRecord->slug == $slug) {
                $existingRecord->delete();
            }
        });
    }

    /**
     * List all locales that are not passed a specific slug
     *
     * @param array $slugs
     * @return array
     */
    private function remainingLocales(array $slugs): array
    {
        $remainingLocales = $this->model->availableLocales();

        foreach ($slugs as $locale => $slug) {
            if ($slug && $locale !== UrlAssistant::WILDCARD) {
                if(false !== ($index = array_search($locale, $remainingLocales)))
                {
                    unset($remainingLocales[$index]);
                }
            }
        }

        return array_values($remainingLocales);
    }

    /**
     * @param string $slug
     * @param $locale
     * @return string
     */
    private function prependBaseUrlSegment(string $slug, $locale): string
    {
        $slugWithBaseSegment = $this->model->baseUrlSegment($locale) . '/' . $slug;
        $slugWithBaseSegment = trim($slugWithBaseSegment, '/');

        // If slug with base segment is empty string, it means that the passed slug was probably a "/" character.
        // so we'll want to return it in case the base segment is not added.
        return $slugWithBaseSegment ?: '/';
    }
}