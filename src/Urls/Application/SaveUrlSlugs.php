<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\Application;

use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Urls\UrlRecord;
use Thinktomorrow\Chief\Urls\ProvidesUrl\BaseUrlSegment;

class SaveUrlSlugs
{
    /** @var bool */
    private $strict = true;

    /** @var ProvidesUrl */
    private $model;

    private $existingRecords;

    public function __construct(ProvidesUrl $model)
    {
        $this->model = $model;
    }

    /**
     * Saving urls slugs in strict mode prevents identical urls to be automatically removed.
     * When set to false, this would remove the identical url records.
     *
     * @param bool $strict
     * @return $this
     */
    public function strict(bool $strict = true)
    {
        $this->strict = $strict;

        return $this;
    }

    public function handle(array $slugs): void
    {
        $this->existingRecords = UrlRecord::getByModel($this->model);

        foreach ($slugs as $locale => $slug) {
            if (!$slug) {
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
        $nonRedirectsWithSameLocale = $this->existingRecords->filter(function ($record) use ($locale) {
            return (
                $record->locale == $locale &&
                !$record->isRedirect()
            );
        });

        // If slug entry is left empty, all existing records will be deleted
        if (!$slug) {
            $nonRedirectsWithSameLocale->each(function ($existingRecord) {
                $existingRecord->delete();
            });

            return;
        }

        $this->cleanupExistingRecords($locale, $slug);

        // If slug entry is left empty, all existing records will be deleted
        if ($nonRedirectsWithSameLocale->isEmpty()) {
            $this->createRecord($locale, $slug);

            return;
        }

        // Only replace the existing records that differ from the current passed slugs
        $nonRedirectsWithSameLocale->each(function ($existingRecord) use ($slug) {
            if ($existingRecord->slug != $slug) {
                $existingRecord->replaceAndRedirect(['slug' => $slug]);
            }
        });
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
     * @param string $locale
     * @param string|null $slug
     */
    private function cleanupExistingRecords(string $locale, string $slug): void
    {
        // In the case where we have any redirects that match the given slug, we need to
        // remove the redirect record in favour of the newly added one.
        $this->deleteIdenticalRedirects($this->existingRecords, $locale, $slug);

        $sameExistingRecords = UrlRecord::where('slug', $slug)->where('locale', $locale)->get();

        // Also delete any redirects that match this locale and slug but are related to another model
        $this->deleteIdenticalRedirects($sameExistingRecords, $locale, $slug);

        // Also delete any urls that match this locale and slug but are related to another model
        $this->deleteIdenticalRecords($sameExistingRecords);
    }

    /**
     * Remove any redirects owned by this model that equal the new slug.
     *
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

    private function deleteIdenticalRecords($existingRecords): void
    {
        if ($this->strict) {
            return;
        }

        // The old homepage url should be removed since this is no longer in effect.
        // In case of any redirect to this old homepage, the last used redirect is now back in effect.
        $existingRecords->reject(function ($existingRecord) {
            return (
                $existingRecord->model_type == $this->model->getMorphClass() &&
                $existingRecord->model_id == $this->model->id);
        })->each(function ($existingRecord) {

            // TODO: if there is a redirect to this page, we'll take this one as the new url
            $existingRecord->delete();
        });
    }

    /**
     * @param string $slug
     * @param $locale
     * @return string
     */
    private function prependBaseUrlSegment(string $slug, $locale): string
    {
        return BaseUrlSegment::prepend($this->model, $slug, $locale);
    }
}
