<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems;

interface TabItem
{
    public function getId(): string;

    public function getTitle(): ?string;

    public function getAllowedSites(): array;

    public function getActiveSites(): array;

    public function addActiveSites(array $activeSites): void;

    public function exists(): bool;
}
