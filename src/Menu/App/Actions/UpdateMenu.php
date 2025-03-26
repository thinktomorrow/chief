<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

class UpdateMenu
{
    private string $menuId;

    private array $sites;

    private ?string $title;

    public function __construct(string $menuId, array $sites, ?string $title = null)
    {
        $this->menuId = $menuId;
        $this->sites = $sites;
        $this->title = $title;
    }

    public function getMenuId(): string
    {
        return $this->menuId;
    }

    public function getSites(): array
    {
        return $this->sites;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
