<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu;

use Illuminate\Support\Collection;

class Menu
{
    /** @var string */
    private $key;

    /** @var string */
    private $label;

    /** @var string */
    private $view_path;

    public function __construct(string $key, string $label, string $view_path)
    {
        $this->label = $label;
        $this->view_path = $view_path;
        $this->key = $key;
    }

    public static function all(): Collection
    {
        $types = config('thinktomorrow.chief.menus', []);

        return collect($types)->map(function ($menu, $key) {
            return new static($key, $menu['label'], $menu['view']);
        });
    }

    public static function find($key): ?self
    {
        return static::all()->filter(function ($menu) use ($key) {
            return $menu->key() == $key;
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

    public function menu(): ChiefMenu
    {
        return ChiefMenu::fromMenuItems($this->key);
    }

    public function items()
    {
        return $this->menu()->items();
    }

    public function render()
    {
        if (view()->exists($this->view_path)) {
            return view($this->view_path, [
                'menu' => $this,
            ])->render();
        }

        if (file_exists($this->view_path)) {
            return file_get_contents($this->view_path);
        }

        return $this->fallbackRender();
    }

    private function fallbackRender()
    {
        $menu = [];

        $this->items()->each(function($item) use(&$menu){
            $menu[] = sprintf('<li><a href="%s">%s</a></li>',$item->url, $item->label);
        });

        return '<ul>'.implode('', $menu).'</ul>';
    }
}
