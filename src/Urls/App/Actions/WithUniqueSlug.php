<?php

namespace Thinktomorrow\Chief\Urls\App\Actions;

use Illuminate\Support\Str;
use Thinktomorrow\Chief\Site\Visitable\BaseUrlSegment;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Urls\Exceptions\UrlAlreadyExists;

trait WithUniqueSlug
{
    private bool $force = false;

    private bool $prependBaseUrlSegment = true;

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
        $slug = $this->prependBaseUrlSegment
            ? BaseUrlSegment::prepend($model, $slug, $site)
            : $slug;

        // convert diacritics to ascii e.g. é -> e.
        $slug = Str::ascii($slug);

        // Convert spaces to hyphen e.g. "my slug" -> "my-slug"
        return str_replace(' ', '-', $slug);
    }

    private function assertSlugDoesNotExistsAsActiveUrl(string $locale, string $slug, ?int $whiteListedId = null)
    {
        $existingRecord = $this->repository->findActiveUrlBySlug($slug, $locale);

        if ($existingRecord && $existingRecord->id != $whiteListedId) {
            throw new UrlAlreadyExists('An Url record (other than id: '.$whiteListedId.') already exists for site: '.$locale.', slug: '.$slug);
        }
    }

    private function cleanupMatchingRedirects(Visitable $model, string $locale, string $slug, ?int $whiteListedId = null): void
    {
        // In the case where we have any redirects that match the given slug, we need to
        // remove the redirect record in favour of the newly added one. Also delete any
        // redirects that match this site and slug but are related to another model
        $sameUrl = $this->repository->findBySlug($slug, $locale);

        if ($sameUrl && $sameUrl->isRedirect()) {
            $this->delete(new DeleteUrl($sameUrl->id));
        }
    }

    /**
     * Delete any urls that match this site and slug And are related to the same model
     */
    private function deleteIdenticalRecordsOfModel(Visitable $model, string $locale, string $slug, ?int $whiteListedId): void
    {
        $identicalUrls = $this->repository->getIdenticalUrlsOfModel($model->modelReference(), $slug, $locale, $whiteListedId);

        $identicalUrls->each(function ($url) {
            $this->delete(new DeleteUrl($url->id));
        });
    }

    /**
     * Delete any urls that match this site and slug but are related to another model
     */
    private function deleteIdenticalRecordsOfOtherModels(Visitable $model, string $locale, string $slug): void
    {
        $sameUrls = $this->repository->getIdenticalUrlsOfOtherModels($model->modelReference(), $slug, $locale);

        $sameUrls->each(function ($url) {
            $this->delete(new DeleteUrl($url->id));
        });
    }
}
