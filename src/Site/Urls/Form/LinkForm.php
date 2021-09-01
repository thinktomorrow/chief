<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Url\Root;
use Thinktomorrow\Chief\ManagedModels\States\WithPageState;

final class LinkForm
{
    private Visitable $model;
    private Collection $urlRecords;

    private Collection $links;
    private Collection $formValues;

    private function __construct(Visitable $model, Collection $urlRecords)
    {
        $this->model = $model;
        $this->urlRecords = $urlRecords;

        $this->setLinks();
        $this->injectOnlineStatusPerLocale();
        $this->setFormValues();
    }

    public static function fromModel(Model $model): self
    {
        return new static($model, \Thinktomorrow\Chief\Site\Urls\UrlRecord::getByModel($model)
            ->groupBy('locale')
            ->map(function ($records) {
                return $records->sortBy('redirect_id')->sortByDesc('created_at');
            }));
    }

    public function links(): Collection
    {
        return $this->links;
    }

    public function formValues(): Collection
    {
        return $this->formValues;
    }

    private function setLinks(): void
    {
        $links = [];

        foreach (config('chief.locales') as $locale) {
            $records = $this->urlRecords->get($locale, collect());
            $currentRecord = $records->reject->isRedirect()->first();

            $url = $this->model->url($locale);

            $links[$locale] = (object)[
                'current' => $currentRecord,
                'url' => $url,
                'full_path' => $url ? trim(substr($url, strlen(Root::fromString($url)->get())), '/') : '',
                'redirects' => $records->filter->isRedirect(),
            ];
        }

        $this->links = collect($links);
    }

    private function injectOnlineStatusPerLocale()
    {
        foreach ($this->links as $locale => $links) {
            [$is_online, $offline_reason] = [
                false,
                'Er is nog geen url voor ' . $locale,
            ];

            if ($links->current) {
                [$is_online, $offline_reason] = $this->determineOnlineStatusInfo($links->current, $locale);
            }

            $this->links[$locale]->is_online = $is_online;
            $this->links[$locale]->offline_reason = $offline_reason;
        }
    }

    public function isAnyLinkOnline(): bool
    {
        foreach ($this->links as $links) {
            if ($links->is_online) {
                return true;
            }
        }

        return false;
    }

    private function setFormValues(): void
    {
        $values = [];

        foreach (config('chief.locales') as $locale) {
            $currentRecord = $this->urlRecords->get($locale, collect())->reject->isRedirect()->first();

            $values[$locale] = (object)[
                'host' => $this->model->resolveUrl($locale, $this->model->baseUrlSegment($locale)) . '/',
                'fixedSegment' => $this->model->baseUrlSegment($locale),
                'value' => $currentRecord
                    ? $this->rawSlugValue($currentRecord->slug, $this->model->baseUrlSegment($locale))
                    : null,
            ];
        }

        $this->formValues = collect($values);
    }

    public function exist(): bool
    {
        return $this->urlRecords->isNotEmpty();
    }

    public function hasAnyRedirects(): bool
    {
        foreach ($this->links as $links) {
            if (! $links->redirects->isEmpty()) {
                return true;
            }
        }

        return false;
    }

    private function rawSlugValue(string $slug, string $baseUrlSegment): string
    {
        // If this is a '/' slug, it indicates the homepage for this locale. In this case,
        // we wont be trimming the slash
        if ($slug === '/') {
            return $slug;
        }

        return $this->removeBaseUrlSegment($slug, $baseUrlSegment);
    }

    private function removeBaseUrlSegment(string $slug, string $baseUrlSegment): string
    {
        if ($baseUrlSegment && 0 === strpos($slug, $baseUrlSegment)) {
            return trim(substr($slug, strlen($baseUrlSegment)), '/');
        }

        return $slug;
    }

    public function getPageState(): ?string
    {
        return $this->model instanceof WithPageState
            ? $this->model->getPageState()
            : PageState::PUBLISHED; // Without pageState behaviour we consider a model to be always published.
    }

    /**
     * @param $currentRecord
     * @param $locale
     * @return array
     */
    private function determineOnlineStatusInfo($currentRecord, $locale): array
    {
        $pagestate = $this->getPageState();
        $is_online = ($pagestate && $pagestate == PageState::PUBLISHED && $currentRecord);

        $offline_reason = 'De pagina staat offline.';

        if (! $is_online) {
            if (! $pagestate) {
                $offline_reason = 'Pagina staat nog niet gepubliceerd.';
            } else {
                if ($pagestate == PageState::DRAFT) {
                    $offline_reason = 'Pagina staat nog in draft. Je dient deze nog te publiceren.';
                } else {
                    if ($pagestate == PageState::ARCHIVED) {
                        $offline_reason = 'De pagina is gearchiveerd.';
                    } else {
                        if ($pagestate == PageState::PUBLISHED && ! $currentRecord) {
                            $offline_reason = 'Pagina staat klaar voor publicatie maar er ontbreekt nog een link voor de ' . $locale . ' taal.';
                        }
                    }
                }
            }
        }

        return [$is_online, $offline_reason];
    }
}
