<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

class UpdateMenu
{
    private string $menuId;

    private array $allowedSites;

    private array $activeSites;

    private ?string $title;

    public function __construct(string $menuId, array $allowedSites, array $activeSites, ?string $title = null)
    {
        $this->menuId = $menuId;
        $this->allowedSites = $allowedSites;
        $this->activeSites = $activeSites;
        $this->title = $title;
    }

    public function getMenuId(): string
    {
        return $this->menuId;
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
