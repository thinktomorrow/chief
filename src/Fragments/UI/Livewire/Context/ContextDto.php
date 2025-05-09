<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\Context;

use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems\TabItem;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\ChiefSites;

class ContextDto implements TabItem, Wireable
{
    const DEFAULT_TITLE = 'Inhoud';

    public function __construct(
        public readonly string $id,
        public readonly ?string $title,
        public readonly ModelReference $ownerReference,
        public readonly string $ownerLabel,
        public readonly string $ownerAdminUrl,
        public readonly array $allowedSites = [],
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
            $model->allowed_sites ?? [],
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
            'allowedSites' => $this->allowedSites,
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
            $value['allowedSites'],
            $value['activeSites'] ?? [],
        );
    }

    public function addActiveSites(array $activeSites): void
    {
        $this->activeSites = array_merge($this->activeSites, $activeSites);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getAllowedSites(): array
    {
        return $this->allowedSites;
    }

    public function getActiveSites(): array
    {
        return $this->activeSites;
    }

    public function exists(): bool
    {
        return ! Str::startsWith($this->id, 'new-');
    }
}
