<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\BaseUrlSegment;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

final class SaveUrlSlugs
{
    /** @var bool */
    private $strict;

    /**
     * Saving urls slugs in strict mode prevents identical urls to be automatically removed.
     * When set to false, this would remove the identical url records.
     */
    public function handle(Visitable $model, array $slugs, bool $strict = true, bool $prependBaseUrlSegment = true): void
    {
        $this->strict = $strict;

        /** @var Collection $existingRecords */
        $existingRecords = UrlRecord::getByModel($model);

        foreach ($slugs as $siteId => $slug) {
            if (! $slug) {
                $this->deleteEmptyRecord($model, $siteId, $existingRecords);

                continue;
            }

            /**
             * We convert any non-ascii characters to their ascii equivalent.
             * The mysql database considers these chars the same in where clauses, e.g. e and é.
             *  This asserts a consistent behaviour in both the application and the database
             */
            //            $slug = Str::ascii($slug);
            $slug = $prependBaseUrlSegment ? $this->prependBaseUrlSegment($model, $slug, $siteId) : $slug;

            $this->saveRecord(
                $model,
                $siteId,
                $slug,
                $existingRecords
            );
        }
    }

    private function deleteEmptyRecord(Visitable $model, string $siteId, Collection $existingRecords): void
    {
        $this->saveRecord($model, $siteId, null, $existingRecords);
    }

    private function saveRecord(Visitable $model, string $siteId, ?string $slug, Collection $existingRecords): void
    {
        // Existing ones for this site?
        $nonRedirectsForSameSite = $existingRecords->filter(function ($record) use ($siteId) {
            return
                $record->site == $siteId &&
                ! $record->isRedirect();
        });

        // If slug entry is left empty, all existing records will be deleted
        if (! $slug) {
            $nonRedirectsForSameSite->each(function ($existingRecord) {
                $existingRecord->delete();
            });

            return;
        }

        $this->cleanupExistingRecords($model, $siteId, $slug, $existingRecords);

        // If there are no matching urls, the url is created
        if ($nonRedirectsForSameSite->isEmpty()) {
            $this->createRecord($model, $siteId, $slug);

            return;
        }

        // Only replace the existing records that differ from the current passed slugs
        $nonRedirectsForSameSite->each(function ($existingRecord) use ($slug) {
            // Non-ascii chars are threated the same in url and will be found as if it were the ascii variant
            // Therefore we can safely update the existing url record instead of creating a redirect first.
            if (Str::ascii($existingRecord->slug) == Str::ascii($slug)) {
                $existingRecord->slug = $slug;
                $existingRecord->save();
            } elseif ($existingRecord->slug != $slug) {
                app(RedirectUrl::class)->handle($existingRecord, $slug);
            }
        });
    }

    private function createRecord(Visitable $model, string $siteId, string $slug): void
    {
        UrlRecord::create([
            'site' => $siteId,
            'slug' => $slug,
            'model_type' => $model->getMorphClass(),
            'model_id' => $model->id,
        ]);
    }

    private function cleanupExistingRecords(Visitable $model, string $siteId, string $slug, Collection $existingRecords): void
    {
        // In the case where we have any redirects that match the given slug, we need to
        // remove the redirect record in favour of the newly added one.
        $this->deleteIdenticalRedirects($existingRecords, $siteId, $slug);

        $sameExistingRecords = UrlRecord::where('slug', $slug)->where('site', $siteId)->get();

        // Also delete any redirects that match this site and slug but are related to another model
        $this->deleteIdenticalRedirects($sameExistingRecords, $siteId, $slug);

        // Also delete any urls that match this site and slug but are related to another model
        if (! $this->strict) {
            $this->deleteIdenticalRecords($model, $sameExistingRecords);
        }
    }

    /**
     * Remove any redirects owned by this model that equal the new slug.
     */
    private function deleteIdenticalRedirects(Collection $existingRecords, string $siteId, string $slug): void
    {
        $existingRecords->filter(function ($record) use ($siteId) {
            return
                $record->site == $siteId &&
                $record->isRedirect();
        })->each(function ($existingRecord) use ($slug) {
            if ($existingRecord->slug == $slug) {
                $existingRecord->delete();
            }
        });
    }

    private function deleteIdenticalRecords(Visitable $model, $existingRecords): void
    {
        // The old homepage url should be removed since this is no longer in effect.
        // In case of any redirect to this old homepage, the last used redirect is now back in effect.
        $existingRecords->reject(function ($existingRecord) use ($model) {
            return
                $existingRecord->model_type == $model->getMorphClass() &&
                $existingRecord->model_id == $model->id;
        })->each(function ($existingRecord) {
            // TODO: if there is a redirect to this page, we'll take this one as the new url
            $existingRecord->delete();
        });
    }

    private function prependBaseUrlSegment(Visitable $model, string $slug, string $siteId): string
    {
        return BaseUrlSegment::prepend($model, $slug, $siteId);
    }
}
