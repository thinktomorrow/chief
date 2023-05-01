<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

trait HasWiredInput
{
    public function formDataIdentifier(string $name, ?string $locale = null): string
    {
        return LivewireAssist::formDataIdentifier($name, $locale);
    }

    public function formDataIdentifierSegment(string $name, ?string $locale = null): ?string
    {
        return LivewireAssist::formDataIdentifierSegment($name, $locale);
    }
}
