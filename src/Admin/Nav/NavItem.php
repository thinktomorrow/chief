<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Nav;

final class NavItem
{
    private string $label;

    private string $url;

    private array $tags;

    private string $icon;

    private bool $isActive = false;

    public function __construct(string $label, string $url, array $tags, string $icon)
    {
        $this->label = $label;
        $this->url = $url;
        $this->tags = $tags;
        $this->icon = $icon;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function icon(): string
    {
        return $this->icon;
    }

    public function isTagged($tags): bool
    {
        return count(array_intersect($this->tags, (array) $tags)) > 0;
    }

    public function isUntagged(): bool
    {
        return count($this->tags) == 0;
    }

    public function detectActive(): self
    {
        $this->isActive = request()->fullUrlIs($this->url) || request()->is($this->url) || request()->is($this->url.'/*');

        return $this;
    }

    public function setActive(bool $active = true): self
    {
        $this->isActive = $active;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive ?? false;
    }
}
