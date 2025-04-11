<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

class UpdateMenu
{
    private string $menuId;

    private array $locales;

    private array $activeSites;

    private ?string $title;

    public function __construct(string $menuId, array $locales, array $activeSites, ?string $title = null)
    {
        $this->menuId = $menuId;
        $this->locales = $locales;
        $this->activeSites = $activeSites;
        $this->title = $title;
    }

    public function getMenuId(): string
    {
        return $this->menuId;
    }

    public function getLocales(): array
    {
        return $this->locales;
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
