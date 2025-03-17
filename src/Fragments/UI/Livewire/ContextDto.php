<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Wireable;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ContextDto implements Wireable
{
    public function __construct(
        public string $contextId,
        public string $label,
        public ModelReference $ownerReference,
        public string $ownerLabel,
        public string $ownerAdminUrl,
        public array $locales = [],
    ) {}

    public static function fromContext(ContextModel $context, ModelReference $ownerReference, string $ownerLabel, string $ownerAdminUrl): self
    {
        return new static(
            $context->id,
            $context->title ?? '',
            $ownerReference,
            $ownerLabel,
            $ownerAdminUrl,
            $context->locales ?? [],
        );
    }

    public function toLivewire()
    {
        return [
            'contextId' => $this->contextId,
            'label' => $this->label,
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
            $value['label'],
            ModelReference::fromString($value['ownerReference']),
            $value['ownerLabel'],
            $value['ownerAdminUrl'],
            $value['locales'],
        );
    }
}
