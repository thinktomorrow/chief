<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Nav;

final class NavItem
{
    private $label;
    private $url;

    /** @var array */
    private $tags;

    public function __construct(string $label, string $url, array $tags = [], string $icon)
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

    // public function render(): string
    // {
    //     $output = '<a class="link link-black' . (isActiveUrl($this->url()) ? 'active' : '') . '" href="' . $this->url() . '">';
    //     $output .= ucfirst($this->label());
    //     $output .= '</a>';

    //     return $output;
    // }
}
