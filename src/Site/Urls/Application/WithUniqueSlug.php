<?php

namespace Thinktomorrow\Chief\Site\Urls\Application;

use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Visitable\BaseUrlSegment;
use Thinktomorrow\Chief\Site\Visitable\Visitable;

trait WithUniqueSlug
{
    private bool $force = false;

    private bool $prependBaseUrlSegment = true;

    private function cleanupIdenticalSlugs(Visitable $model, string $locale, string $slug, ?int $whiteListedId = null): void
    {
        // In the case where we have any redirects that match the given slug, we need to
        // remove the redirect record in favour of the newly added one. Also delete any
        // redirects that match this site and slug but are related to another model
        $this->deleteIdenticalRedirects($locale, $slug);

        if ($this->force) {
            $this->deleteIdenticalRecords($model, $locale, $slug, $whiteListedId);
            $this->deleteOtherIdenticalRecords($model, $locale, $slug);
        }
    }

    /**
     * Remove any redirects owned by this model that equal the new slug.
     */
    private function deleteIdenticalRedirects(string $locale, string $slug): void
    {
        $sameUrls = UrlRecord::where('slug', $slug)->where('site', $locale)->get();

        $sameUrls->filter(function ($url) use ($locale) {
            return $url->site == $locale && $url->isRedirect();
        })->each(function ($url) use ($slug) {
            if ($url->slug == $slug) {
                app(DeleteUrl::class)->handle($url->id);
            }
        });
    }

    /**
     * Delete any urls that match this site and slug And are related to the same model
     */
    private function deleteIdenticalRecords(Visitable $model, string $locale, string $slug, ?int $whiteListedId): void
    {
        $sameUrls = UrlRecord::where('slug', $slug)
            ->when($whiteListedId, function ($query, $whiteListedId) {
                return $query->whereNot('id', $whiteListedId);
            })
            ->where('site', $locale)
            ->where('model_type', $model->getMorphClass())
            ->where('model_id', $model->id)
            ->get();

        $sameUrls->each(function ($url) {
            app(DeleteUrl::class)->handle($url->id);
        });
    }

    /**
     * Delete any urls that match this site and slug but are related to another model
     */
    private function deleteOtherIdenticalRecords(Visitable $model, string $locale, string $slug): void
    {
        $sameUrls = UrlRecord::where('slug', $slug)
            ->where('site', $locale)
            ->whereNot(function ($query) use ($model) {
                $query->where('model_type', $model->getMorphClass())
                    ->where('model_id', $model->id);
            })
            ->get();

        $sameUrls->each(function ($url) {
            app(DeleteUrl::class)->handle($url->id);
        });
    }

    /**
     * If forced, any existing identical slugs belonging to other
     * models will be removed in favor of the new one.
     */
    public function force(bool $force = true): self
    {
        $this->force = $force;

        return $this;
    }

    public function prependBaseUrlSegment(bool $prependBaseUrlSegment = true): self
    {
        $this->prependBaseUrlSegment = $prependBaseUrlSegment;

        return $this;
    }

    private function composeSlug(Visitable $model, string $site, string $slug): string
    {
        return $this->prependBaseUrlSegment
            ? BaseUrlSegment::prepend($model, $slug, $site)
            : $slug;
    }

    private function assertSlugDoesNotExistsAsActiveUrl(string $locale, string $slug, ?int $whiteListedId = null)
    {
        $existingRecord = UrlRecord::where('slug', $slug)
            ->where('site', $locale)
            ->whereNull('redirect_id')
            ->first();

        if ($existingRecord && $existingRecord->id != $whiteListedId) {
            throw new \Exception('An Url record (other than id: '.$whiteListedId.') already exists for site: '.$locale.', slug: '.$slug);
        }
    }
}
