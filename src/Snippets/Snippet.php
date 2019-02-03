<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Snippets;

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

    public function key()
    {
        return $this->key;
    }

    public function placeholder()
    {
        return "[[$this->key]]";
    }

    public function label()
    {
        return $this->label;
    }

    public function path()
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
