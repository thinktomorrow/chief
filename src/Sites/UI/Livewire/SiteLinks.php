<?php

namespace Thinktomorrow\Chief\Sites\UI\Livewire;

use Illuminate\Support\Arr;
use Livewire\Component;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Repositories\ContextRepository;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Site\Urls\LinkStatus;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\Actions\SyncLocales;
use Thinktomorrow\Chief\Sites\BelongsToSites;
use Thinktomorrow\Chief\Sites\ChiefSite;
use Thinktomorrow\Chief\Sites\ChiefSites;

class SiteLinks extends Component
{
    public string $resourceKey;

    public string $modelReference;

    public array $siteIds = [];

    public bool $inEditMode = false;

    //    public array $activeSites = [];
    //    public array $currentLocales = [];
    //    public bool $showConfirmButton = false;
    //    public $warningMessage;
    //    public bool $isSaving = false;

    public function mount(string $resourceKey, Visitable&BelongsToSites&ReferableModel&ContextOwner $model)
    {
        $this->resourceKey = $resourceKey;
        $this->modelReference = $model->modelReference()->get();
        $this->siteIds = $model->getSiteIds();
    }

    public function getActiveSiteLinks(): array
    {
        return $this->getSiteLinks();
    }

    public function getAllSiteLinks(): array
    {
        return $this->getSiteLinks(true);
    }

    public function getSiteLinks(bool $includeMissingLinks = false): array
    {
        $siteLinks = [];
        $missingLinks = [];

        $sites = ChiefSites::all()->filterByIds($this->siteIds);
        $model = ModelReference::fromString($this->modelReference)->instance();
        $contexts = app(ContextRepository::class)->getByOwner($model);
        $activeRecords = $model->urls;

        /** @var ChiefSite $site */
        foreach ($sites as $site) {

            // Get current url for this site...
            $activeRecord = $activeRecords->filter(fn ($record) => $record->site == $site->id)->first();

            if (! $activeRecord && $includeMissingLinks) {
                $missingLinks[] = new SiteLink(
                    $site->id,
                    null,
                    null,
                    null,
                    LinkStatus::none,
                );

                continue;
            }

            $siteLinks[$site->id] = new SiteLink(
                $site->id,
                $activeRecord->context_id,
                $activeRecord->context_id ? $contexts->filter(fn ($context) => $context->id == $activeRecord->context_id)->first()?->title : null,
                new LinkUrl($activeRecord->id, $model->url($site->id), $activeRecord->slug),
                LinkStatus::from($activeRecord->status),
            );

        }

        return array_merge($siteLinks, $missingLinks);
    }

    public function getLinkStatusOptions(): array
    {
        return LinkStatus::options();
    }

    // Get all sites ...
    // Get all current urls of this model: bepaalt welke 'sites' er actief staan

    // Per site:
    // (Is site active for this model ... ?) -> ni naar kijken...
    // Get the current url of this model ...
    // Get active context_id for this site...

    // Get all previous redirects of this model...
    // Get Status of this site: online, offline, scheduled for online, scheduled for offline, redirect, temporary redirect, ...

    public function submit()
    {
        if ($this->shouldConfirmFirst()) {
            $this->isSaving = false;
            $this->askConfirmation();

            return;
        }

        // Small timed delay to make it look like a big deal and we can see the spinner
        usleep(400 * 1000);

        app(SyncLocales::class)->handle(
            $this->resourceKey,
            $model = ModelReference::fromString($this->modelReference)->instance(),
            $this->activeLocales,
        );

        $this->js('window.location.reload()');

        $this->close();
    }

    private function shouldConfirmFirst()
    {
        return ! $this->showConfirmButton && $this->isAboutToRemoveLocales();
    }

    public function isAboutToRemoveLocales(): bool
    {
        return count($this->getRemovedLocales()) > 0;
    }

    public function getRemovedLocales()
    {
        $removedLocales = [];

        foreach ($this->currentLocales as $locale) {
            if (! in_array($locale, $this->activeLocales)) {
                $removedLocales[] = $locale;
            }
        }

        return $removedLocales;
    }

    private function askConfirmation()
    {
        $this->showConfirmButton = true;
        $this->warningMessage = 'Opgelet! Als u <strong>'.$this->getRemovedLocalesAsString().'</strong> verwijdert, zullen ook de links en inhoud worden verwijderd van deze pagina.';
    }

    public function close()
    {
        $this->reset(['warningMessage', 'showConfirmButton', 'isSaving']);
        $this->activeLocales = $this->currentLocales;
        $this->isOpen = false;
    }

    public function getRemovedLocalesAsString(): string
    {
        return Arr::join(Arr::map($this->getRemovedLocales(), fn ($locale) => strtoupper($locale)), ', ', ' en ');
    }

    public function render()
    {
        return view('chief-sites::site-links');
    }
}
