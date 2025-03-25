<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

class CreateMenu
{
    private string $type;

    private array $sites;

    private ?string $title;

    public function __construct(string $type, array $sites, ?string $title = null)
    {
        $this->type = $type;
        $this->sites = $sites;
        $this->title = $title;
    }

    public function getType(): string
    {
        return $this->type;
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
