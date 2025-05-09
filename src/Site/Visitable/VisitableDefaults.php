<?php

namespace Thinktomorrow\Chief\Site\Visitable;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Symfony\Component\HttpFoundation\Response;
use Thinktomorrow\Chief\ManagedModels\States\Publishable\PreviewMode;
use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Sites\HasAllowedSites;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;

trait VisitableDefaults
{
    use ResolvingRoute;

    /** {@inheritdoc} */
    public function url(?string $site = null): ?string
    {
        if (! $site) {
            $site = app()->getLocale();
        }

        if (! $urlRecord = $this->urls->first(fn ($urlRecord) => $urlRecord->site == $site)) {
            return null;
        }

        if (! ChiefSites::all()->exists($site) || $urlRecord->isOffline()) {
            return null;
        }

        return $this->resolveUrl($site, [$urlRecord->slug]);
    }

    public function rawUrl(?string $site = null): ?string
    {
        if (! $site) {
            $site = app()->getLocale();
        }

        if (! $urlRecord = $this->urls->first(fn ($urlRecord) => $urlRecord->site == $site)) {
            return null;
        }

        return $this->resolveUrl($site, [$urlRecord->slug]);
    }

    public function urls(): HasMany
    {
        return $this->hasMany(UrlRecord::class, 'model_id')
            ->where('model_type', $this->getMorphClass())
            ->whereNull('redirect_id');
    }

    public function allUrls(): HasMany
    {
        return $this->hasMany(UrlRecord::class, 'model_id')
            ->where('model_type', $this->getMorphClass());
    }

    public function scopeOnline(Builder $query, ?string $site = null): void
    {
        if (! $site) {
            $site = app()->getLocale();
        }

        if ($this instanceof HasAllowedSites) {
            $query->byAllowedSiteOrNone($site);
        }

        if ($this instanceof StatefulContract) {
            $query->published();
        }

        $query->withOnlineUrl($site);
    }

    public function scopeWithOnlineUrl(Builder $query, ?string $site = null): void
    {
        if (! $site) {
            $site = app()->getLocale();
        }

        $query->whereHas('urls', function ($relatedBuilder) use ($site) {
            return $relatedBuilder
                ->where('site', $site)
                ->where('status', LinkStatus::online->value);
        });
    }

    public function isVisitable(): bool
    {
        if ($this instanceof StatefulContract && ! $this->inOnlineState()) {
            // When admin is logged in and this request is in preview mode, we allow the view
            return PreviewMode::fromRequest()->check();
        }

        return true;
    }

    // TODO: just used once (in url preview) so cant we just remove this?...
    public function resolveUrl(?string $locale = null, $parameters = null): string
    {
        $routeName = config('chief.route.name');

        return $this->resolveRoute($routeName, $parameters, $locale);
    }

    /** {@inheritdoc} */
    public function baseUrlSegment(?string $site = null): string
    {
        return BaseUrlSegment::find(isset(static::$baseUrlSegment) ? (array) static::$baseUrlSegment : [], $site);
    }

    public function response(): Response
    {
        return new \Illuminate\Http\Response(
            $this->renderView()
        );
    }
}
