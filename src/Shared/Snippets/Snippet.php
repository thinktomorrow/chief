<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Snippets;

class Snippet
{
    /** @var string */
    private $key;

    /** @var string */
    private $label;

    /** @var string */
    private $path;

    public function __construct(string $key, string $label, string $path)
    {
        $this->label = $label;
        $this->path = $path;
        $this->key = $key;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function placeholder(): string
    {
        return "[[$this->key]]";
    }

    public function label(): string
    {
        return $this->label;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function render()
    {
        if (($bladePath = str_replace('.blade.php', '', $this->path)) && view()->exists($bladePath)) {
            return view($bladePath)->render();
        }

        if (file_exists($this->path)) {
            return file_get_contents($this->path);
        }

        return '';
    }
}
