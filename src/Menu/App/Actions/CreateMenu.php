<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

class CreateMenu
{
    private string $type;

    private array $locales;

    private array $activeSites;

    private ?string $title;

    public function __construct(string $type, array $locales, array $activeSites, ?string $title = null)
    {
        $this->type = $type;
        $this->locales = $locales;
        $this->activeSites = $activeSites;
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
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
