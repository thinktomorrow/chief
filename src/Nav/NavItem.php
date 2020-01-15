<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Nav;

class NavItem
{
    private $title;
    private $url;

    /** @var array */
    private $details;

    public function __construct($title, $url, array $details = [])
    {
        $this->title = $title;
        $this->url = $url;
        $this->details = $details;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function url(): string
    {
        return $this->url;
    }

    public function details($key = null, $default = null)
    {
        if ($key) {
            return $this->details[$key] ?? $default;
        }

        return $this->details;
    }
}
