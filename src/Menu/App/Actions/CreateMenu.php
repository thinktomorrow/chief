<?php

namespace Thinktomorrow\Chief\Menu\App\Actions;

class CreateMenu
{
    private string $type;

    private array $locales;

    private ?string $title;

    public function __construct(string $type, array $locales, ?string $title = null)
    {
        $this->type = $type;
        $this->locales = $locales;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
