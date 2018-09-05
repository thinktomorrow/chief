<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Snippets;

use Illuminate\Support\Collection;

class Snippet
{
    /** @var string */
    private $key;

    /** @var string */
    private $label;

    /** @var string */
    private $view_path;

    public function __construct(string $key, string $label, string $view_path)
    {
        $this->label     = $label;
        $this->view_path = $view_path;
        $this->key       = $key;
    }

    public static function all(): Collection
    {
        $types = config('thinktomorrow.chief-settings.snippets', []);

        return collect($types)->map(function($snippet, $key){
            return new static($key, $snippet['label'], $snippet['view']);
        });
    }

    public static function find($key): ?self
    {
        return static::all()->filter(function($snippet) use($key){
            return $snippet->key() == $key;
        })->first();
    }

    public function key()
    {
        return $this->key;
    }

    public function label()
    {
        return $this->label;
    }

    public function viewPath()
    {
        return $this->view_path;
    }

    public function render()
    {
        if (view()->exists($this->view_path)) {
            return view($this->view_path,[
                'snippet' => $this,
            ])->render();
        }

        if(file_exists($this->view_path)) {
            return file_get_contents($this->view_path);
        }

        return '';
    }

    public static function renderForClips()
    {
        $result = [];
        foreach(self::all() as $snippet){
            $view   = $snippet->render();
            $view = trim(preg_replace('/\s+/', ' ', $view));
            $view   = str_replace('\'', '"', $view);
            $result = '[\''. $snippet->label . '\', \'' . str_replace(['<', '>'], ['\<', '\>'], $view) . '\']';
        }

        return $result;
    }
}