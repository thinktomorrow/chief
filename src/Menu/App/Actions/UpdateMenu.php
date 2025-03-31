<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

class UpdateMenu
{
    private string $menuId;

    private array $locales;

    private ?string $title;

    public function __construct(string $menuId, array $locales, ?string $title = null)
    {
        $this->menuId = $menuId;
        $this->locales = $locales;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
