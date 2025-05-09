<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

class CreateMenu
{
    private string $type;

    private array $allowedSites;

    private array $activeSites;

    private ?string $title;

    public function __construct(string $type, array $allowedSites, array $activeSites, ?string $title = null)
    {
        $this->type = $type;
        $this->allowedSites = $allowedSites;
        $this->activeSites = $activeSites;
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getAllowedSites(): array
    {
        return $this->allowedSites;
    }

    public function getActiveSites(): array
    {
        return $this->activeSites;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
