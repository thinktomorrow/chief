<?php

namespace Thinktomorrow\Chief\Forms\Livewire;

trait PacksComponentsForLivewire
{
    private function packComponentsToLivewire(): array
    {
        $converted = [];

        foreach ($this->getComponents() as $component) {
            $converted[] = $component->toLivewire();
        }

        return $converted;
    }

    private static function unpackComponentsFromLivewire(array $packedComponents): array
    {
        return collect($packedComponents)->map(function ($component) {
            return $component['class']::fromLivewire($component);
        })->all();
    }
}
