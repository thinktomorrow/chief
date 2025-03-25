<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Illuminate\Support\Str;
use Livewire\Wireable;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Sites\Locales\ChiefLocales;

class ContextDto implements Wireable
{
    public function __construct(
        public string $contextId,
        public string $title,
        public ModelReference $ownerReference,
        public string $ownerLabel,
        public string $ownerAdminUrl,
        public array $locales = [],
    ) {}

    public static function fromContext(ContextModel $context, ModelReference $ownerReference, string $ownerLabel, string $ownerAdminUrl): self
    {
        return new static(
            $context->id,
            $context->title ?? 'Default',
            $ownerReference,
            $ownerLabel,
            $ownerAdminUrl,
            $context->locales ?? [],
        );
    }

    public static function empty(ModelReference $ownerReference, string $ownerLabel, string $ownerAdminUrl): static
    {
        return new static(
            'new-'.Str::random(),
            '',
            $ownerReference,
            $ownerLabel,
            $ownerAdminUrl,
            ChiefLocales::locales(),
        );
    }

    public function toLivewire()
    {
        return [
            'contextId' => $this->contextId,
            'title' => $this->title,
            'ownerReference' => $this->ownerReference->get(),
            'ownerLabel' => $this->ownerLabel,
            'ownerAdminUrl' => $this->ownerAdminUrl,
            'locales' => $this->locales,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            $value['contextId'],
            $value['title'],
            ModelReference::fromString($value['ownerReference']),
            $value['ownerLabel'],
            $value['ownerAdminUrl'],
            $value['locales'],
        );
    }
}
