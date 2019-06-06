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

            if(!$slug){
                $this->deleteRecord($locale);
                continue;
            }

            $this->saveRecord($locale, $this->prependBaseUrlSegment($slug, $locale));
        }
    }

    private function deleteRecord(string $locale)
    {
        return $this->saveRecord($locale, null);
    }

    private function saveRecord(string $locale, ?string $slug)
    {
        // Existing ones for this locale?
        $nonRedirectsWithSameLocale = $this->existingRecords->filter(function($record) use($locale){
            return (
                $record->locale == $locale &&
                !$record->isRedirect()
            );
        });

        // In the case where we have any redirects that match the given slug, we need to
        // remove the redirect record in favour of the newly added one.
        $this->deleteIdenticalRedirects($this->existingRecords, $locale, $slug);

        // Also delete any redirects that match this locale and slug but are related to another model
        $this->deleteIdenticalRedirects(UrlRecord::where('slug',$slug)->where('locale',$locale)->get(), $locale, $slug);

        // If slug entry is left empty, all existing records will be deleted
        if(!$slug){
            $nonRedirectsWithSameLocale->each(function($existingRecord){
                $existingRecord->delete();
            });
        }
        elseif($nonRedirectsWithSameLocale->isEmpty()){
            $this->createRecord($locale, $slug);
        }
        else{
            // Only replace the existing records that differ from the current passed slugs
            $nonRedirectsWithSameLocale->each(function($existingRecord) use($slug){
                if($existingRecord->slug != $slug){
                    $existingRecord->replaceAndRedirect(['slug' => $slug]);
                }
            });
        }
    }

    private function createRecord($locale, $slug)
    {
        UrlRecord::create([
            'locale'              => $locale,
            'slug'                => $slug,
            'model_type'          => $this->model->getMorphClass(),
            'model_id'            => $this->model->id,
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
            return (
                $record->locale == $locale &&
                $record->isRedirect()
            );
        })->each(function ($existingRecord) use ($slug) {
            if ($existingRecord->slug == $slug) {
                $existingRecord->delete();
            }
        });
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