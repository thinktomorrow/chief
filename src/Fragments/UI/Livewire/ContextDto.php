<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\ChiefSites;

class ContextDto implements Wireable
{
    const DEFAULT_TITLE = 'Inhoud';

    public function __construct(
        public string $id,
        public ?string $title,
        public ModelReference $ownerReference,
        public string $ownerLabel,
        public string $ownerAdminUrl,
        public array $locales = [],
        public array $activeSites = [],
    ) {}

    public static function fromContext(ContextModel $model, ModelReference $ownerReference, string $ownerLabel, string $ownerAdminUrl): self
    {
        return new static(
            $model->id,
            $model->title ?? self::DEFAULT_TITLE,
            $ownerReference,
            $ownerLabel,
            $ownerAdminUrl,
            $model->locales ?? [],
            $model->active_sites ?? [],
        );
    }

    public static function empty(ModelReference $ownerReference, string $ownerLabel, string $ownerAdminUrl): static
    {
        return new static(
            'new-'.Str::random(),
            null,
            $ownerReference,
            $ownerLabel,
            $ownerAdminUrl,
            ChiefSites::locales(),
            [],
        );
    }

    public function toLivewire()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'ownerReference' => $this->ownerReference->get(),
            'ownerLabel' => $this->ownerLabel,
            'ownerAdminUrl' => $this->ownerAdminUrl,
            'locales' => $this->locales,
            'activeSites' => $this->activeSites,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            $value['id'],
            $value['title'],
            ModelReference::fromString($value['ownerReference']),
            $value['ownerLabel'],
            $value['ownerAdminUrl'],
            $value['locales'],
            $value['activeSites'] ?? [],
        );
    }

    public function addActiveSites(array $activeSites): void
    {
        $this->activeSites = array_merge($this->activeSites, $activeSites);
    }

    //    public function hasSpecificLocales(): bool
    //    {
    //        return count(\Thinktomorrow\Chief\Sites\ChiefSites::verifiedLocales($this->locales)) < count(\Thinktomorrow\Chief\Sites\ChiefSites::locales());
    //    }

}
