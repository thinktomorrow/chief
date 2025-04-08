<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Wireable;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

readonly class SharedFragmentDto implements Wireable
{
    public function __construct(
        public string $fragmentId,
        public string $contextId,
        public string $contextLabel,
        public ModelReference $ownerReference,
        public string $ownerLabel,
        public string $ownerAdminUrl,
    ) {}

    public static function fromContext(string $fragmentId, ContextModel $context, ModelReference $ownerReference, string $ownerLabel, string $ownerAdminUrl): self
    {
        return new static(
            $fragmentId,
            $context->id,
            $context->title ?? '',
            $ownerReference,
            $ownerLabel,
            $ownerAdminUrl,
        );
    }

    public function toLivewire()
    {
        return [
            'fragmentId' => $this->fragmentId,
            'contextId' => $this->contextId,
            'contextLabel' => $this->contextLabel,
            'ownerReference' => $this->ownerReference->get(),
            'ownerLabel' => $this->ownerLabel,
            'ownerAdminUrl' => $this->ownerAdminUrl,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            $value['fragmentId'],
            $value['contextId'],
            $value['contextLabel'],
            ModelReference::fromString($value['ownerReference']),
            $value['ownerLabel'],
            $value['ownerAdminUrl'],
        );
    }
}
