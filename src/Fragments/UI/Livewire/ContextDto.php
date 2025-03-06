<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire;

use Livewire\Wireable;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;

class ContextDto implements Wireable
{
    public function __construct(
        public string $contextId,
        public string $label,
        public array $locales = [],
    ) {}

    public static function fromContext(ContextModel $context): self
    {
        return new static(
            $context->id,
            $context->title ?? '',
            $context->locales ?? [],
        );
    }

    public function toLivewire()
    {
        return [
            'contextId' => $this->contextId,
            'label' => $this->label,
            'locales' => $this->locales,
        ];
    }

    public static function fromLivewire($value)
    {
        return new static(
            $value['contextId'],
            $value['label'],
            $value['locales'],
        );
    }
}
